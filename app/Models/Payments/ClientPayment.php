<?php

namespace App\Models\Payments;

use App\Helpers\Helpers;
use App\Models\Business\SoldPolicy;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClientPayment extends Model
{
    const MORPH_TYPE = 'client_payment';
    use HasFactory;

    const FILES_DIRECTORY = 'sold_policies/client_pymt_docs/';

    const PYMT_TYPE_CASH = 'cash';
    const PYMT_TYPE_CHEQUE = 'cheque';
    const PYMT_TYPE_BANK_TRNSFR = 'bank_transfer';
    const PYMT_TYPE_VISA = 'visa';
    const PYMT_TYPE_SALES_OUT = 'sales_out';

    const PYMT_TYPES = [
        self::PYMT_TYPE_CASH,
        self::PYMT_TYPE_CHEQUE,
        self::PYMT_TYPE_BANK_TRNSFR,
        self::PYMT_TYPE_VISA,
        self::PYMT_TYPE_SALES_OUT,
    ];

    const PYMT_STATE_NEW = 'new';
    const PYMT_STATE_PREM_COLLECTED = 'prem_collected';
    const PYMT_STATE_PAID = 'paid';
    const PYMT_STATE_CANCELLED = 'cancelled';
    const PYMT_STATES = [
        self::PYMT_STATE_NEW,
        self::PYMT_STATE_PREM_COLLECTED,
        self::PYMT_STATE_PAID,
        self::PYMT_STATE_CANCELLED,
    ];
    const NOT_PAID_STATES = [
        self::PYMT_STATE_NEW,
        self::PYMT_STATE_PREM_COLLECTED,
    ];

    const PYMT_PAID_STATES = [
        self::PYMT_STATE_PREM_COLLECTED,
        self::PYMT_STATE_PAID
    ];

    protected $table = 'client_payments';
    protected $fillable = [
        'status',
        'type',
        'amount',
        'note',
        'payment_date',
        'doc_url',
        'due',
        'closed_by_id',
        'assigned_to',
        'sales_out_id'
    ];

    ///model functions
    public function setInfo(Carbon $due, $type,  $assigned_to_id, $note = null, $sales_out_id = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            AppLog::info("Setting Client Payment info", loggable: $this);
            return $this->update([
                "due"           =>  $due->format('Y-m-d'),
                "type"          =>  $type,
                "sales_out_id"  =>  $sales_out_id,
                "note"          =>  $note,
                "assigned_to"   =>  $assigned_to_id,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Client Payment info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function setAssignedTo($assigned_to_id)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            AppLog::info("Setting Client Payment assignee", loggable: $this);
            return $this->update([
                "assigned_to"  =>  $assigned_to_id,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Client Payment assignee failed", desc: $e->getMessage(), loggable: $this);
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
            AppLog::info("Setting Client Payment document", loggable: $this);
            $this->update([
                'doc_url'   =>  $doc_url
            ]);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Setting Client Payment document failed", desc: $e->getMessage(), loggable: $this);
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
            AppLog::info("Deleting Client Payment document", loggable: $this);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Deleting Client Payment document failed", desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    public function setAsPremiumCollected($doc_url = null, string $note = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        if ($this->is_new || is_null($this->status)) {
            try {

                AppLog::info("Setting Client Payment as prem collected", loggable: $this);
                if ($doc_url) {
                    $updates['doc_url'] = $doc_url;
                }

                if ($note) {
                    $updates['note'] = $note;
                }
                $updates['status'] = self::PYMT_STATE_PREM_COLLECTED;
                return $this->update($updates);
            } catch (Exception $e) {
                report($e);
                AppLog::error("Setting Client Payment info failed", desc: $e->getMessage(), loggable: $this);
            }
        }
    }

    public function setAsPaid($payment_type = null, Carbon $date = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('pay', $this)) return false;

        if (!$this->is_collected) return false;
        try {
            $date = $date ?? new Carbon();
            AppLog::info("Setting Client Payment as paid", loggable: $this);
            $updates['closed_by_id'] = Auth::id();
            $updates['payment_date'] = $date->format('Y-m-d H:i');
            $updates['status'] = self::PYMT_STATE_PAID;
            if ($payment_type) {
                $updates['type'] = $payment_type;
            }

            if ($this->update($updates)) {
                $this->load('sold_policy');
                $this->sold_policy->setClientPaymentDate($date);
                $this->sold_policy->generatePolicyCommissions(true);
                $this->sold_policy->calculateTotalClientPayments();
                $this->sold_policy->updateSalesCommsPaymentInfo();
            }
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Client Payment info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function setAsCancelled(Carbon $date = null)
    {
        /** @var User */
        $user = Auth::user();
        if ($this->is_paid) {
            if (!$user->can('updateIfCancelled', $this)) return false;
        } else {
            if (!$user->can('update', $this)) return false;
        }


        try {
            $date = $date ?? new Carbon();
            AppLog::info("Setting Client Payment as cancelled", loggable: $this);
            $wasPaid = $this->status == self::PYMT_STATE_PAID;
            $res = $this->update([
                "closed_by_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_CANCELLED,
            ]);
            if ($res && $wasPaid) {
                $this->load('sold_policy');
                $this->sold_policy->setClientPaymentDate(null);
                $this->sold_policy->setClientCancellationDate(null);
                $this->sold_policy->generatePolicyCommissions(true);
                $this->sold_policy->calculateTotalClientPayments();
                $this->sold_policy->updateSalesCommsPaymentInfo();
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Client Payment info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function deletePayment()
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        if ($this->is_new || is_null($this->status)) {
            try {
                AppLog::info("Deleting Client Payment", loggable: $this);
                return $this->delete();
            } catch (Exception $e) {
                report($e);
                AppLog::error("Deleting Client Payment failed", desc: $e->getMessage(), loggable: $this);
            }
        }
    }

    ///attributes
    public function getIsNewAttribute()
    {
        return $this->status == self::PYMT_STATE_NEW;
    }
    public function getIsCollectedAttribute()
    {
        return $this->status == self::PYMT_STATE_PREM_COLLECTED;
    }

    ///scopes
    public function scopeUserData($query, array $states = [self::PYMT_STATE_NEW], $assigned_only = false, string $searchText = null, $upcoming_only = false)
    {
        /** @var User */
        $user = Auth::user();
        $canSeeAll = $user->can('viewAny', self::class);

        $query->select('client_payments.*')
            ->leftjoin('sold_policies', 'sold_policies.id', '=', 'client_payments.sold_policy_id')
            ->leftjoin('sales_comms', 'sales_comms.sold_policy_id', '=', 'sold_policies.id')
            ->leftjoin('comm_profiles', 'comm_profiles.id', '=', 'sales_comms.comm_profile_id')
            ->groupBy('client_payments.id');

        if (!$canSeeAll) $query->where(
            function ($q) use ($user) {
                $q->where('sold_policies.main_sales_id', $user->id)
                    // ->orwhere('sold_policies.creator_id', $user->id) 
                    ->orwhere('comm_profiles.user_id', $user->id)
                    ->orwhere('client_payments.assigned_to', $user->id);
            }
        );

        if ($assigned_only) $query->where('client_payments.assigned_to', $user->id);

        $query->when(count($states), fn($q) => $q->filterByStates($states));

        $query->when($searchText, function ($q, $s) {
            $q->searchBy($s);
        });

        $query->when($upcoming_only, function ($q) {
            $now = new Carbon();
            $q->whereBetween('client_payments.due', [
                $now->format('Y-m-01'),
                $now->addMonth()->format('Y-m-t')
            ]);
        });

        $query->orderByDesc('client_payments.due');
        return $query;
    }

    public function scopePaid(Builder $query)
    {
        return $query->where('client_payments.status', self::PYMT_STATE_PAID);
    }

    public function scopeNotPaidOnly(Builder $query)
    {
        return $query->whereNot('status', self::PYMT_STATE_PAID);
    }

    public function scopeFilterByStates(Builder $query, array $states)
    {
        // When Filter is NEW show NEW & NULL payments
        if (count($states)) {
            if (in_array(self::PYMT_STATE_NEW, $states)) {
                $query->where(function ($q) use ($states) {
                    $q->whereIn('client_payments.status', $states)
                        ->orWhereNull('client_payments.status');
                });
            } else {
                $query->whereIn('client_payments.status', $states);
            }
        }
        return $query;
    }

    public function scopeSearchBy(Builder $query, $searchText)
    {
        if (!Helpers::joined($query, "sold_policies")) {
            $query->join('sold_policies', 'sold_policies.id', '=', 'client_payments.sold_policy_id');
        }
        return $query->where('sold_policies.policy_number', "LIKE", "%$searchText%");
    }

    public function scopeByCompany(Builder $query, $company_id)
    {
        if (!Helpers::joined($query, "sold_policies")) {
            $query->join('sold_policies', 'sold_policies.id', '=', 'client_payments.sold_policy_id');
        }
        if (!Helpers::joined($query, "policies")) {
            $query->join('policies', 'sold_policies.policy_id', '=', 'policies.id');
        }
        return $query->where('policies.company_id', "=", $company_id);
    }

    public function scopeIncludeDue(Builder $query)
    {
        if (!Helpers::joined($query, 'sold_policies')) {
            $query->join('sold_policies', 'sold_policies.id', '=', 'client_payments.sold_policy_id');
        }

        $query->join('policy_comm_conf', function ($j) {
            $j->on('sold_policies.policy_id', '=', 'policy_comm_conf.policy_id')
                ->where('is_main_penalty', 1);
        })->select('client_payments.*', 'policy_comm_conf.due_penalty', 'policy_comm_conf.value', 'policy_comm_conf.penalty_percent', 'policy_comm_conf.calculation_type', 'sold_policies.net_premium')
            ->selectRaw('IF( sold_policies.created_at > sold_policies.start, sold_policies.created_at , sold_policies.start)  policy_payment_due')
            ->groupBy('client_payments.id');
    }

    //Must use with include due
    public function scopeDueAfter(Builder $query, $days)
    {
        return $query->whereRaw("
            ( DATE_ADD( IF( sold_policies.created_at > sold_policies.start, sold_policies.created_at , sold_policies.start), INTERVAL policy_comm_conf.due_penalty DAY) > NOW() 
            AND 
            DATEDIFF( 
                DATE_ADD( IF( sold_policies.created_at > sold_policies.start, sold_policies.created_at , sold_policies.start), INTERVAL policy_comm_conf.due_penalty DAY) ,
                NOW() 
            ) <= $days+1 ) ");
    }

    //Must use with include due
    public function scopeDuePassed(Builder $query, $days)
    {
        return $query->whereRaw("
            ( DATE_ADD( IF( sold_policies.created_at > sold_policies.start, sold_policies.created_at , sold_policies.start), INTERVAL policy_comm_conf.due_penalty DAY) < NOW() 
            AND 
            DATEDIFF( 
              NOW() ,
              DATE_ADD( IF( sold_policies.created_at > sold_policies.start, sold_policies.created_at , sold_policies.start), INTERVAL policy_comm_conf.due_penalty DAY)  
            ) <= $days+1 ) ");
    }

    ///relations
    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }
    public function closed_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by_id');
    }
    public function assigned(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
