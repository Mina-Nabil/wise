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
use Illuminate\Support\Facades\Auth;
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
        'status', 'title', 'amount', 'note', 'payment_date', 'doc_url', 'comm_percentage', 'sold_policy_id', 'user_id', 'from', 'client_paid_percent', 'company_paid_percent'
    ];

    ///model functions
    public function setPaidInfo(float $client_paid_percent, float $company_paid_percent)
    {
        //TODO : shof ezay hat update el comm profile balance
        $updates['client_paid_percent'] = $client_paid_percent;
        $updates['company_paid_percent'] = $company_paid_percent;

        try {
            $this->load('comm_profile');
            //balance calculation
            $company_diff_amount = round(($company_paid_percent - $this->company_paid_percent) * $this->amount / 100,2);
            $add_to_balance = $company_diff_amount;

            //unapproved balance calculation 
            $client_diff_amount = round(($client_paid_percent - $this->client_paid_percent) * $this->amount / 100,2);

            $old_unapproved_offset = round($this->client_paid_percent * $this->amount,2) - round($this->company_paid_percent * $this->amount,2);
            $add_to_unapproved_balance = 0;
            if ($company_diff_amount > 1 && $old_unapproved_offset > 0) {
                $add_to_unapproved_balance += max($client_diff_amount - $company_diff_amount, $old_unapproved_offset);
            } else if($client_diff_amount) {
                $add_to_unapproved_balance += max($client_diff_amount - $old_unapproved_offset, 0);
            }

            $this->comm_profile->balance = $this->comm_profile->balance + $add_to_balance;
            $this->comm_profile->unapproved_balance = $this->comm_profile->unapproved_balance + $add_to_unapproved_balance;


            $this->comm_profile->save();
            $this->update($updates);
            AppLog::info("Setting comm profile paid info",  loggable: $this);
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
            $this->loadMissing('sold_policy');
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

    public function refreshPaymentInfo()
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        AppLog::info("Calculating Sales Comm amount", loggable: $this);
        $this->load('sold_policy');
        switch ($this->from) {
            case CommProfileConf::FROM_NET_PREM:
                $from_amount = $this->sold_policy->net_premium;
                break;
            case CommProfileConf::FROM_NET_COMM:
                $this->sold_policy->calculateTotalPolicyComm();
                $from_amount =  $this->sold_policy->total_policy_comm;
                break;
        }

        $amount = ($this->comm_percentage / 100) * $from_amount;
        try {
            return $this->update([
                "amount"            =>  $amount,
            ]);
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
                $this->loadMissing('sold_policy');
                $this->sold_policy->calculateTotalSalesCommPaid();
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
            return $this->update([
                "closed_by_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_CANCELLED,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Sales Comm info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function delete()
    {
        $this->loadMissing('offer');
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

    ///scopes
    public function scopeNew(Builder $query)
    {
        $query->where('status', self::PYMT_STATE_NOT_CONFIRMED);
    }

    public function scopePaid(Builder $query)
    {
        $query->where('status', self::PYMT_STATE_PAID);
    }

    ///relations
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
