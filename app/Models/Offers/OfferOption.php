<?php

namespace App\Models\Offers;

use App\Models\Insurance\Policy;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class OfferOption extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_NEW = 'new';
    const STATUS_DECLINED = 'declined';
    const STATUS_APPROVED = 'approved';

    const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_DECLINED,
        self::STATUS_APPROVED,
    ];

    const PAYMENT_FREQ_YEARLY = 'yearly';
    const PAYMENT_FREQ_MONTHLY = 'monthly';
    const PAYMENT_FREQ_QUARTER = 'quarter';
    const PAYMENT_FREQS = [
        self::PAYMENT_FREQ_YEARLY,
        self::PAYMENT_FREQ_MONTHLY,
        self::PAYMENT_FREQ_QUARTER,
    ];

    protected $table = 'offer_options';
    protected $fillable = [
        'status', 'policy_id', 'policy_condition_id',
        'insured_value', 'periodic_payment', 'payment_frequency', 'approved_by_id'
    ];

    ////static functions


    ////model functions
    public function editInfo($insured_value, $payment_frequency)
    {
        switch ($payment_frequency) {
            case self::PAYMENT_FREQ_YEARLY:
                $periodic_payment = $insured_value;
                break;
            case self::PAYMENT_FREQ_QUARTER:
                $periodic_payment = round($insured_value / 4, 2);
                break;
            case self::PAYMENT_FREQ_MONTHLY:
                $periodic_payment = round($insured_value / 12, 2);
                break;

            default:
                return false;
        }
        try {
            if ($this->update([
                "insured_value"  =>  $insured_value,
                "periodic_payment"  =>  $periodic_payment,
                "payment_frequency"  =>  $payment_frequency,
            ])) {
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
            if ($this->docs()->create([
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
    ////scopes


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
        return $this->belongsTo(Offer::class);
    }
}
