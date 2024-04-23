<?php

namespace App\Models\Payments;

use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommProfilePayment extends Model
{
    use HasFactory;

    const FILES_DIRECTORY = 'sold_policies/comm_prof_pymt_docs/';

    const PYMT_TYPE_CASH = 'cash';
    const PYMT_TYPE_CHEQUE = 'cheque';
    const PYMT_TYPE_BANK_TRNSFR = 'bank_transfer';

    const PYMT_TYPES = [
        self::PYMT_TYPE_CASH,
        self::PYMT_TYPE_CHEQUE,
        self::PYMT_TYPE_BANK_TRNSFR,
    ];

    const PYMT_STATE_NEW = 'new';
    const PYMT_STATE_PAID = 'paid';
    const PYMT_STATE_APPROVED = 'approved';
    const PYMT_STATE_CANCELLED = 'cancelled';
    const PYMT_STATES = [
        self::PYMT_STATE_NEW,
        self::PYMT_STATE_PAID,
        self::PYMT_STATE_APPROVED,
        self::PYMT_STATE_CANCELLED,
    ];

    protected $table = 'client_payments';
    protected $fillable = [
        'status', 'type', 'amount', 'note', 'payment_date', 'doc_url', 'needs_approval', 'creator_id', 'approver_id'
    ];

    ///model functions
    public function setInfo($amount, $type, $note = null)
    {
        assert($this->status == self::PYMT_STATE_NEW, "Payment is not new, can't be updated");
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            AppLog::info("Setting Profile Payment info", loggable: $this);
            return $this->update([
                "amount"  =>  $amount,
                "type"  =>  $type,
                "note"  =>  $note,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Profile Payment info failed", desc: $e->getMessage(), loggable: $this);
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
            AppLog::info("Setting Profile Payment document", loggable: $this);
            $this->update([
                'doc_url'   =>  $doc_url
            ]);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Setting Profile Payment document failed", desc: $e->getMessage(), loggable: $this);
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
            AppLog::info("Deleting Profile Payment document", loggable: $this);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Deleting Profile Payment document failed", desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    public function approve()
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('approve', $this)) return false;
        try {
            $this->update([
                "approval_date" =>  Carbon::now()->format('Y-m-d H:i:s'),
                "approver_id"   =>  Auth::id(),
                "status"        =>  self::PYMT_STATE_APPROVED
            ]);
            AppLog::info("Comm Payment approved by " . Auth::user()->username, loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Comm Payment approve failed by " . Auth::user()->username, loggable: $this, desc: $e);
            return false;
        }
    }

    public function setAsPaid(Carbon $date = null)
    {
        if ($this->needs_approve && !$this->is_approved) throw new Exception("Payment not approved");
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        if (!$this->is_new || !$this->is_approved) return false;

        try {
            DB::transaction(function () use ($date) {
                $this->load('comm_profile');
                if ($this->needs_approve) {
                    $this->comm_profile->unapproved_balance = $this->comm_profile->unapproved_balance - ($this->amount - $this->comm_profile->balance);
                    $this->balance = 0;
                } else {
                    $this->comm_profile->balance = $this->comm_profile->balance - $this->amount;
                }

                $this->comm_profile->save();

                $date = $date ?? new Carbon();
                AppLog::error("Setting Profile Payment as paid", loggable: $this);
                $this->update([
                    "payment_date"  => $date->format('Y-m-d H:i'),
                    "status"  =>  self::PYMT_STATE_PAID,
                ]);
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Profile Payment info failed", desc: $e->getMessage(), loggable: $this);
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
            AppLog::error("Setting Profile Payment as cancelled", loggable: $this);
            return $this->update([
                "closed_by_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_CANCELLED,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Profile Payment info failed", desc: $e->getMessage(), loggable: $this);
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
    public function getIsApprovedAttribute()
    {
        return $this->status == self::PYMT_STATE_APPROVED;
    }

    ///scopes
    public function scopePaid(Builder $query)
    {
        $query->where('status', self::PYMT_STATE_PAID);
    }

    ///relations
    public function comm_profile(): BelongsTo
    {
        return $this->belongsTo(CommProfile::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
