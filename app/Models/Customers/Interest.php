<?php

namespace App\Models\Customers;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Interest extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'customer_interests';

    protected $table = 'customer_interests';

    protected $fillable = [
        "business",
        "interested",
        "note"
    ];


    ///model functions
    public function editInterest($business, bool $interested, $note = null)
    {
        try {
            $this->update([
                "business"      =>  $business,
                "interested"    =>  $interested,
                "note"    =>  $note
            ]);
            AppLog::info("Editing customer interest", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editing customer interest failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function delete()
    {
        $customer = $this->customer;
        try {
            if (parent::delete()) {
                AppLog::info("Customer interest deleted", loggable: $customer);
            } else {
                AppLog::info("Customer interest deletetion failed", loggable: $customer);
            }
            return true;
        } catch (Exception $e) {
            AppLog::info("Customer interest deletetion failed", loggable: $customer);
            report($e);
            return false;
        }
    }

    ///relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
