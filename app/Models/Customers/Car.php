<?php

namespace App\Models\Customers;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_cars';
    protected $fillable = [
        "car_id",
        "value",
        "sum_insured",
        "insurance_payment",
        "payment_frequency",
    ];


    //model functions
    public function editInfo($car_id, $value = null, $sum_insured = null, $insurance_payment = null, $payment_frequency = null)
    {
        try {
            $this->update([
                "car_id"      =>  $car_id,
                "value"      =>  $value,
                "sum_insured"  =>  $sum_insured,
                "insurance_payment"    =>  $insurance_payment,
                "payment_frequency"     =>  $payment_frequency
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
}
