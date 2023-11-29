<?php

namespace App\Models\Corporates;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Address extends Model
{
    use HasFactory;

    protected $table = 'corporate_addresses';

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
                DB::table('corporate_addresses')->where('corporate_id', $this->corporate_id)->update([
                    'is_default'    =>  false
                ]);
                $this->is_default = true;
                $this->save();
            });
            AppLog::info('Address set as default', loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Can\'t set address as default', desc: $e->getMessage(), loggable: $this);
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
            AppLog::info("Editing corporate address", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editing corporate address failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Corporate::class);
    }
}
