<?php

namespace App\Models\Offers;

use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyCondition;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfferOption extends Model
{
    use HasFactory, SoftDeletes;

    const MORPH_TYPE = 'offer_options';

    const STATUS_RQST_QTTN = 'request_qoutation';
    const STATUS_RJCT_BY_OPER = 'rejected_by_operation';
    const STATUS_RJCT_BY_INS = 'rejected_by_insurance';
    const STATUS_QTTN_RECV = 'qoutation_received';
    const STATUS_CLNT_ACPT = 'client_accepted';
    const STATUS_ISSUED = 'issued';

    const STATUSES = [
        self::STATUS_RQST_QTTN,
        self::STATUS_RJCT_BY_OPER,
        self::STATUS_RJCT_BY_INS,
        self::STATUS_QTTN_RECV,
        self::STATUS_CLNT_ACPT,
        self::STATUS_ISSUED,
    ];

    const PAYMENT_FREQ_YEARLY = 'yearly';
    const PAYMENT_FREQ_MONTHLY = 'monthly';
    const PAYMENT_FREQ_QUARTER = 'quarterly';
    const PAYMENT_FREQ_HALF_YEARLY = 'half-yearly';
    const PAYMENT_INSTALLEMENTS = 'installements';

    const PAYMENT_FREQS = [
        self::PAYMENT_FREQ_YEARLY,
        self::PAYMENT_FREQ_MONTHLY,
        self::PAYMENT_FREQ_QUARTER,
        self::PAYMENT_FREQ_HALF_YEARLY,
        self::PAYMENT_INSTALLEMENTS
    ];

    const PAYMENT_FREQS_ARBC = [
        self::PAYMENT_FREQ_YEARLY => 'سنوي',
        self::PAYMENT_FREQ_MONTHLY => 'شهري',
        self::PAYMENT_FREQ_QUARTER => 'ربع سنوي',
        self::PAYMENT_FREQ_HALF_YEARLY=> 'نص سنوي',
        self::PAYMENT_INSTALLEMENTS=> 'اقساط',
    ];

    protected $table = 'offer_options';
    protected $fillable = [
        'status', 'policy_id', 'policy_condition_id',
        'insured_value',
        'payment_frequency',
        'approved_by_id',
        'net_premium',
        'gross_premium',
        'is_renewal',
        'installements_count'
    ];

    ////static functions


    ////model functions
    public function editInfo(
        $insured_value = null,
        $net_premium = null,
        $gross_premium = null,
        $payment_frequency = null,
        $is_renewal = false,
        $installements_count = null
    ) {
        try {
            if ($this->update([
                "insured_value"  =>  $insured_value,
                "net_premium"  =>  $net_premium,
                "gross_premium"  =>  $gross_premium,
                "payment_frequency"  =>  $payment_frequency,
                "is_renewal"  =>  $is_renewal,
                "installements_count"  =>  $installements_count

            ])) {
                return true;
            } else {
                AppLog::error("Can't edit offer option", desc: "No stack found", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit offer option", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function addField($name, $value)
    {
        try {
            if ($this->fields()->create([
                "name"  =>  $name,
                "user_id"   =>  Auth::id(),
                "value"  =>  $value,
            ])) {
                AppLog::info("Option Field added", loggable: $this);
                return true;
            } else {
                AppLog::error("Option Field addition failed", desc: "Failed to add option field", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Option field addition failed", desc: $e->getMessage(), loggable: $this);
            return true;
        }
    }

    public function addFile($name, $url)
    {
        try {
            if ($this->docs()->create([
                "name"  =>  $name,
                "user_id"   =>  Auth::id(),
                "url"  =>  $url,
            ])) {
                AppLog::info("Option File added", loggable: $this);
                return true;
            } else {
                AppLog::error("Option File addition failed", desc: "Failed to add option file", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Option File addition failed", desc: $e->getMessage(), loggable: $this);
            return true;
        }
    }

    public function deleteOption()
    {
        /** @var User $loggedInUser */
        $loggedInUser = Auth::user(); 
        if(!$loggedInUser->can('delete', $this)) {
            return false;
        }

        DB::beginTransaction();
        try {
            $this->fields()->delete();
            $this->docs()->delete();
            $this->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return false;
        }
    }

    ////scopes
    public function scopeClientSelected()
    {
        return $this->where('status', self::STATUS_CLNT_ACPT);
    }

    ////relations
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function docs(): HasMany
    {
        return $this->hasMany(OptionDoc::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(OptionField::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function policy_condition(): BelongsTo
    {
        return $this->belongsTo(PolicyCondition::class);
    }
}
