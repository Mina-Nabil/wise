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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyCommPayment extends Model
{
    use HasFactory, SoftDeletes;

    const FILES_DIRECTORY = 'sold_policies/company_comm_docs/';

    const PYMT_STATE_NEW = 'new';
    const PYMT_STATE_PAID = 'paid';
    const PYMT_STATE_CANCELLED = 'cancelled';
    const PYMT_STATES = [
        self::PYMT_STATE_NEW,
        self::PYMT_STATE_PAID,
        self::PYMT_STATE_CANCELLED,
    ];

    protected $table = 'company_comm_payments';
    protected $fillable = [
        'status', 'type', 'amount', 'note', 'payment_date', 'doc_url'
    ];

    ///model functions
    public function setInfo($type, $note = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            AppLog::error("Setting Company Comm info", loggable: $this);
            return $this->update([
                "type"  =>  $type,
                "note"  =>  $note,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Company Comm info failed", desc: $e->getMessage(), loggable: $this);
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
            AppLog::info("Setting Comm Payment document", loggable: $this);
            $this->update([
                'doc_url'   =>  $doc_url
            ]);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Setting Comm Payment document failed", desc: $e->getMessage(), loggable: $this);
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
            if ($this->doc_url)
                Storage::delete($this->doc_url);
            AppLog::info("Deleting Comm Payment document", loggable: $this);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Deleting Comm Payment document failed", desc: $e->getMessage(), loggable: $this);
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
            AppLog::error("Setting Company Comm as paid", loggable: $this);
            if ($this->update([
                "receiver_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_PAID,
            ])) {
                $this->loadMissing('sold_policy');
                $this->sold_policy->calculateTotalCompanyPayments();
            }
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Company Comm info failed", desc: $e->getMessage(), loggable: $this);
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
            AppLog::error("Setting Company Comm as cancelled", loggable: $this);
            return $this->update([
                "receiver_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_CANCELLED,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Company Comm info failed", desc: $e->getMessage(), loggable: $this);
        }
    }
    ///attributes
    public function getIsNewAttribute()
    {
        return $this->status == self::PYMT_STATE_NEW;
    }

    ///scopes
    public function scopePaid(Builder $query)
    {
        $query->where('status', self::PYMT_STATE_PAID);
    }

    ///relations
    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
