<?php

namespace App\Models\Payments;

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

    const PYMT_TYPES = [
        self::PYMT_TYPE_CASH,
        self::PYMT_TYPE_CHEQUE,
        self::PYMT_TYPE_BANK_TRNSFR,
        self::PYMT_TYPE_VISA,
    ];

    const PYMT_STATE_NEW = 'new';
    const PYMT_STATE_PAID = 'paid';
    const PYMT_STATE_CANCELLED = 'cancelled';
    const PYMT_STATES = [
        self::PYMT_STATE_NEW,
        self::PYMT_STATE_PAID,
        self::PYMT_STATE_CANCELLED,
    ];

    protected $table = 'client_payments';
    protected $fillable = [
        'status', 'type', 'amount', 'note', 'payment_date', 'doc_url', 'due', 'closed_by_id', 'assigned_to'
    ];

    ///model functions
    public function setInfo(Carbon $due, $type,  $assigned_to_id, $note = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            AppLog::info("Setting Client Payment info", loggable: $this);
            return $this->update([
                "due"   =>  $due->format('Y-m-d'),
                "type"  =>  $type,
                "note"  =>  $note,
                "assigned_to"  =>  $assigned_to_id,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Client Payment info failed", desc: $e->getMessage(), loggable: $this);
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

    public function setAsPaid(Carbon $date = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        if (!$this->is_new) return false;
        try {
            $date = $date ?? new Carbon();
            AppLog::info("Setting Client Payment as paid", loggable: $this);
            if ($this->update([
                "closed_by_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_PAID,
            ])) {
                $this->loadMissing('sold_policy');
                $this->sold_policy->setClientPaymentDate($date);
                $this->sold_policy->generatePolicyCommissions();
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
        if (!$user->can('update', $this)) return false;

        if (!$this->is_new) return false;
        try {
            $date = $date ?? new Carbon();
            AppLog::info("Setting Client Payment as cancelled", loggable: $this);
            return $this->update([
                "closed_by_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_CANCELLED,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Client Payment info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function delete()
    {
        return $this->setAsCancelled();
    }

    ///attributes
    public function getIsNewAttribute()
    {
        return $this->status == self::PYMT_STATE_NEW;
    }

    ///scopes
    public function scopeUserData($query, array $states = [self::PYMT_STATE_NEW], $assigned_only = false)
    {
        /** @var User */
        $user = Auth::user();
        $canSeeAll = $user->can('viewAny', self::class);

        $query->select('client_payments.*')
            ->join('sold_policies', 'sold_policies.id', '=', 'client_payments.sold_policy_id')
            ->leftjoin('policy_watchers', 'policy_watchers.sold_policy_id', '=', 'sold_policies.id')
            ->groupBy('client_payments.id');

        if (!$canSeeAll) $query->where(
            function ($q) use ($user) {
                $q->where('sold_policies.main_sales_id', $user->id)
                    ->orwhere('sold_policies.creator_id', $user->id)
                    ->orwhere('policy_watchers.user_id', $user->id);
            }
        );

        if ($assigned_only) $query->where('client_payments.assigned_to', $user->id);
        $query->whereIn('status', $states);
        return $query;
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
    public function closed_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by_id');
    }
    public function assigned_to(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
