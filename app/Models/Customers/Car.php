<?php

namespace App\Models\Customers;

use App\Models\Cars\Car as CarsCar;
use App\Models\Insurance\Company;
use App\Models\Users\AppLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;
    const MORPH_TYPE = 'customer_car';

    const PAYMENT_FREQ_YEARLY = 'yearly';
    const PAYMENT_FREQ_MONTHLY = 'monthly';
    const PAYMENT_FREQ_QUARTER = 'quarter';
    const PAYMENT_FREQS = [
        self::PAYMENT_FREQ_YEARLY,
        self::PAYMENT_FREQ_MONTHLY,
        self::PAYMENT_FREQ_QUARTER,
    ];

    protected $table = 'customer_cars';
    protected $fillable = [
        "car_id",
        "sum_insured",
        "model_year",
        "insurance_payment",
        "payment_frequency",
        "insurance_company_id",
        "renewal_date",
        "wise_insured",
    ];


    //model functions
    public function editInfo($car_id, $model_year = null, $sum_insured = null, $insurance_payment = null, $payment_frequency = null,  $insurance_company_id = null, Carbon $renewal_date = null, $wise_insured = null)
    {
        try {
            $this->update([
                "car_id"        =>  $car_id,
                "model_year"    =>  $model_year,
                "sum_insured"   =>  $sum_insured,
                "insurance_payment"    =>  $insurance_payment,
                "payment_frequency"     =>  $payment_frequency,
                "insurance_company_id"     =>  $insurance_company_id,
                "renewal_date"     =>  $renewal_date ? $renewal_date->format('Y-m-d H:i:s') : null,
                "wise_insured"     =>  $wise_insured,
            ]);
            AppLog::info("Adding customer car", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding customer car failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }


    ///relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(CarsCar::class);
    }

    public function insurance_company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'insurance_company_id');
    }
}
