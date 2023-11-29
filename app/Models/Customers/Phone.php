<?php

namespace App\Models\Customers;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Phone extends Model
{
    use HasFactory;

    protected $table = 'customer_phones';

    const TYPE_MOBILE = 'mobile';
    const TYPE_HOME = 'home';
    const TYPE_WORK = 'work';
    const TYPE_OTHER = 'other';

    const TYPES = [
        self::TYPE_MOBILE,
        self::TYPE_HOME,
        self::TYPE_WORK,
        self::TYPE_OTHER
    ];

    protected $fillable = [
        "type",
        "number",
        "is_default"
    ];


    ///model functions
    public function setAsDefault()
    {
        try {
            DB::transaction(function () {
                DB::table('customer_phones')->where('customer_id', $this->customer_id)->update([
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

    public function editInfo($type, $number)
    {
        try {
            $this->update([
                "type"      =>  $type,
                "number"    =>  $number,
            ]);
            AppLog::info("Editing customer phone", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editing customer phone failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
