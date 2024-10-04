<?php

namespace App\Models\Payments;

use App\Models\Business\SoldPolicy;
use App\Models\Offers\Offer;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SalesComm extends Model
{
    const MORPH_TYPE = 'sales_comm';

    use HasFactory;
    const PYMT_STATE_NOT_CONFIRMED        = 'not_confirmed';
    const PYMT_STATE_CONFIRMED  = 'confirmed';
    const PYMT_STATE_PAID       = 'paid';
    const PYMT_STATE_CANCELLED  = 'cancelled';
    const PYMT_STATES = [
        self::PYMT_STATE_NOT_CONFIRMED,
        self::PYMT_STATE_CONFIRMED,
        self::PYMT_STATE_PAID,
        self::PYMT_STATE_CANCELLED,
    ];

    const FILES_DIRECTORY = 'sold_policies/sales_comm_docs/';
    protected $table = 'sales_comms';
    protected $fillable = [
        'status',
        'title',
        'amount',
        'note',
        'payment_date',
        'doc_url',
        'comm_percentage',
        'sold_policy_id',
        'user_id',
        'from',
        'client_paid_percent',
        'company_paid_percent',
        'comm_profile_id',
        'unapproved_balance_offset',
        'created_at',
        'is_direct'
    ];

    ///static functions
    public static function getBySoldPoliciesIDs($comm_profile_id, $sold_policies_ids)
    {
        return self::whereIn('sold_policy_id', $sold_policies_ids)
            ->where('comm_profile_id', $comm_profile_id)
            ->get();
    }

    ///model functions
    public function setPaidInfo(float $client_paid_percent, float $company_paid_percent)
    {
        if (!$this->is_confirmed || !$this->is_direct) return false;
        // Log::info("client percentage: " . $client_paid_percent);
        // Log::info("company percentage: " . $company_paid_percent);
        $updates['client_paid_percent'] = $client_paid_percent;
        $updates['company_paid_percent'] = $company_paid_percent;

        try {
            $this->load('comm_profile');
            //balance calculation
            $company_diff_amount = round(($company_paid_percent - $this->company_paid_percent) * $this->amount / 100, 2);
            $add_to_balance = $company_diff_amount;

            //unapproved balance calculation 
            $client_diff_amount = round(($client_paid_percent - $this->client_paid_percent) * $this->amount / 100, 2);
            // Log::info("client diff: " . $client_diff_amount);
            // Log::info("company diff: " . $company_diff_amount);
            $new_offset = $client_diff_amount - $company_diff_amount;
            if ($new_offset > 0) {
                $add_to_unapproved_balance = $new_offset;
            } else {
                $add_to_unapproved_balance = max($new_offset, -1 * $this->unapproved_balance_offset);
            }


            $this->comm_profile->balance = $this->comm_profile->balance + $add_to_balance;
            $this->comm_profile->unapproved_balance = $this->comm_profile->unapproved_balance + $add_to_unapproved_balance;
            $this->comm_profile->save();

            $updates['unapproved_balance_offset'] = $this->unapproved_balance_offset + $new_offset;
            AppLog::info("Setting comm profile paid info",  loggable: $this);
            $this->update($updates);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't set comm profile paid info", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setInfo($title, $comm_percentage, $note = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            AppLog::error("Setting Sales Comm info", loggable: $this);
            $this->load('sold_policy');
            $amount = ($comm_percentage / 100) * $this->sold_policy->gross_premium;
            return $this->update([
                "title"             =>  $title,
                "comm_percentage"   =>  $comm_percentage,
                "amount"            =>  $amount,
                "note"              =>  $note,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Sales Comm info failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    /** Should be used while target calculation only */
    public function updatePaymentByTarget(Target $t, $is_manual = false)
    {

        if ($is_manual) {
            /** @var User */
            $user = Auth::user();
            if (!$user->can('update', $this)) return false;
        }
        $this->clearPreviousPaymentTargetInfo();

        $this->load('sold_policy');

        $this->sold_policy->calculateTotalPolicyComm();
        $from_amount =  $this->sold_policy->total_policy_comm;
        $target_amount = ($t->comm_percentage / 100) * $from_amount;

        try {
            $this->comm_target_runs()->firstOrCreate([
                'target_id' =>  $t->id
            ], [
                'percentage'    =>  $t->comm_percentage,
                'amount'    =>  $target_amount,
            ]);

            $this->load('comm_target_runs');

            $this->update([
                "comm_percentage"   =>  $this->comm_target_runs->sum('percentage'),
                "amount"   =>  $this->comm_target_runs->sum('amount'),
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Sales Comm info failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    private function clearPreviousPaymentTargetInfo()
    {
        $prev = Carbon::now()->subMinutes(2);
        $this->comm_target_runs()
            ->where('created_at', '<=', $prev->format('Y-m-d H:i:s'))
            ->delete();
        $this->load('comm_target_runs');
        $this->comm_percentage =  $this->comm_target_runs->sum('percentage');
        $this->amount = $this->comm_target_runs->sum('amount');
        $this->save();
    }

    public function refreshPaymentInfo($check_user = true, $increment_amount = false, $update_soldpolicy = true)
    {
        if ($check_user) {
            /** @var User */
            $user = Auth::user();
            if (!$user->can('update', $this)) return false;
            AppLog::info("Calculating Sales Comm amount", loggable: $this);
        }

        $this->load('sold_policy');
        $from_amount = 0;
        switch ($this->from) {
            case CommProfileConf::FROM_NET_PREM:
                $from_amount = $this->sold_policy->net_premium;
                break;
            case CommProfileConf::FROM_SUM_INSURED:
                $from_amount = $this->sold_policy->insured_value;
                break;
            case CommProfileConf::FROM_NET_COMM:
                $this->sold_policy->calculateTotalPolicyComm();
                $from_amount =  $this->sold_policy->total_policy_comm;
                break;
        }

        $amount = ($this->comm_percentage / 100) * $from_amount;

        try {
            if ($increment_amount) {
                $this->increment("amount",  $amount);
            } else {
                $this->update(["amount" =>  $amount]);
            }
            if ($update_soldpolicy)
                $this->sold_policy->calculateTotalSalesComm();
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Calculating Sales Comm amount failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setAsPaid(Carbon $date = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        if (!$this->is_new) return false;
        try {
            $date = $date ?? new Carbon();
            AppLog::error("Setting Sales Comm as paid", loggable: $this);
            if ($this->update([
                "closed_by_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_PAID,
            ])) {
                $this->load('sold_policy');
                $this->sold_policy->calculateTotalSalesComm();
                return true;
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Sales Comm info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function setAsCancelled(Carbon $date = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        if (!$this->is_new) return false;
        try {
            $date = $date ?? new Carbon();
            AppLog::error("Setting Sales Comm as cancelled", loggable: $this);
            $this->update([
                "closed_by_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_CANCELLED,
            ]);
            $this->load('sold_policy');
            $this->sold_policy->calculateTotalSalesComm();
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Sales Comm info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function confirmPayment()
    {

        if (!$this->is_new || $this->comm_percentage <= 0) return false;
        try {
            $this->update([
                "status"  =>  self::PYMT_STATE_CONFIRMED,
            ]);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Sales Comm info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function delete()
    {
        $this->load('offer');
        if ($this->offer->is_approved) $this->setAsCancelled();
        else {
            return parent::delete();
        }
    }

    public function setDocument($doc_url)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            if ($this->doc_url)
                Storage::delete($this->doc_url);
            AppLog::info("Setting Sales Comm document", loggable: $this);
            $this->update([
                'doc_url'   =>  $doc_url
            ]);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Setting Sales Comm document failed", desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    public function deleteDocument()
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;
        try {
            if ($this->doc_url) {
                Storage::delete($this->doc_url);
                $this->doc_url = null;
                $this->save();
            }
            AppLog::info("Deleting Sales Comm document", loggable: $this);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Deleting Sales Comm document failed", desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    ///attributes
    public function getIsNewAttribute()
    {
        return $this->status == self::PYMT_STATE_NOT_CONFIRMED;
    }
    public function getIsSalesOutAttribute()
    {
        $this->load('comm_profile');
        return $this->comm_profile->type == CommProfile::TYPE_SALES_OUT;
    }

    ///scopes
    public function scopeNew(Builder $query)
    {
        $query->where(function ($q) {
            $q->where('status', self::PYMT_STATE_NOT_CONFIRMED)
                ->orwhere('status', self::PYMT_STATE_CONFIRMED);
        });
    }

    public function scopeNotCancelled(Builder $query)
    {
        $query->whereNot('status', self::PYMT_STATE_CANCELLED);
    }

    public function scopeNotConfirmed(Builder $query)
    {
        $query->where('status', self::PYMT_STATE_NOT_CONFIRMED);
    }

    public function scopeConfirmed(Builder $query)
    {
        $query->where('status', self::PYMT_STATE_CONFIRMED);
    }

    public function scopePaid(Builder $query)
    {
        $query->where('status', self::PYMT_STATE_PAID);
    }

    public function scopeBySoldPoliciesStartEnd(Builder $query, Carbon $start, Carbon $end)
    {
        return $query->join('sold_policies', 'sold_policies.id', '=', 'sales_comms.sold_policy_id')
            ->whereBetween('sold_policies.created_at', [
                $start->format('Y-m-d'),
                $end->format('Y-m-d')
            ]);
    }

    ///relations
    public function comm_target_runs(): HasMany
    {
        return $this->hasMany(CommTargetRun::class);
    }

    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }
    public function comm_profile(): BelongsTo
    {
        return $this->belongsTo(CommProfile::class);
    }
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
