<?php

namespace App\Models\Customers;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Address extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'customer_address';

    protected $table = 'customer_addresses';

    const TYPE_HOME = 'home';
    const TYPE_WORK = 'work';
    const TYPE_OTHER = 'other';

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
    public function setAsDefault()
    {
        try {
            DB::transaction(function () {
                DB::table('customer_addresses')->where('customer_id', $this->customer_id)->update([
                    'is_default'    =>  false
                ]);
                $this->is_default = true;
                $this->save();
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Can\'t set phone as default', desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

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
            AppLog::info("Editing customer address", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editing customer address failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
