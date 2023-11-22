<?php

namespace App\Models\Customers;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $table = 'customer_addresses';

    const TYPE_HOME = 'home';
    const TYPE_WORK = 'work';
    const TYPE_OTHER = 'work';

    const TYPES = [
        self::TYPE_HOME,
        self::TYPE_WORK,
        self::TYPE_OTHER
    ];

    protected $fillable = [
        "type",
        "line_1",
        "line_2",
        "flat",
        "building",
        "city",
        "country",
    ];


    ///model functions
    public function editInfo($type, $line_1, $line_2 = null, $country = null, $city = null, $building = null, $flat = null)
    {
        try {
            $this->update([
                "type"      =>  $type,
                "line_1"    =>  $line_1,
                "line_2"    =>  $line_2,
                "flat"      =>  $flat,
                "building"  =>  $building,
                "city"      =>  $city,
                "country"   =>  $country,
            ]);
            AppLog::info("Editting customer address", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editting customer address failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
