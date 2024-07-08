<?php

namespace App\Models\Payments;

use App\Models\Users\AppLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Target extends Model
{
    const MORPH_TYPE = 'target';

    use HasFactory;

    const PERIOD_MONTH = 'month';
    const PERIOD_QUARTER = 'quarter';
    const PERIOD_YEAR = 'year';
    const PERIOD_YEAR_TO_DATE = 'year-to-date';

    const PERIODS = [
        self::PERIOD_MONTH,
        self::PERIOD_QUARTER,
        self::PERIOD_YEAR,
        self::PERIOD_YEAR_TO_DATE
    ];

    public $fillable = [
        "period", "prem_target", "comm_percentage", "order", "income_target"
    ];
    public $timestamps = false;

    ///static functions
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    ///model functions
    /** Must be called from daily job */
    public function addTargetPayments()
    {

    }

    public function editInfo(
        $period,
        $prem_target,
        $income_target,
        $comm_percentage
    ) {
        try {
            AppLog::info("Updating comm profile target", loggable: $this);
            $this->update([
                "period"    =>  $period,
                "comm_percentage"    =>  $comm_percentage,
                "income_target"    =>  $income_target,
                "prem_target"    =>  $prem_target,
            ]);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't update comm profile target", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function moveUp()
    {
        $this->loadMissing('comm_profile', 'comm_profile.targets');
        $targets = $this->comm_profile->targets->sortByDesc('order');
        $swap = false;
        foreach ($targets as $target) {
            if ($swap) {
                $tmpOrder = $target->order;
                $target->order = $this->order;
                $this->order = $tmpOrder;
                try {
                    DB::transaction(function () use ($target) {
                        $target->save();
                        $this->save();
                    });
                    AppLog::info('Target Orders adjusted', null, $this->comm_profile);
                    return true;
                } catch (Exception $e) {
                    report($e);
                    AppLog::error("Can't adjust target order", $e->getMessage(), $this->comm_profile);
                    return false;
                }
            }
            if ($target->id == $this->id) {
                $swap = true;
            }
        }
        return true;
    }

    public function moveDown()
    {
        $this->loadMissing('comm_profile', 'comm_profile.targets');
        $targets = $this->comm_profile->targets->sortBy('order');
        $swap = false;
        foreach ($targets as $target) {
            if ($swap) {
                $tmpOrder = $target->order;
                $target->order = $this->order;
                $this->order = $tmpOrder;
                try {
                    DB::transaction(function () use ($target) {
                        $target->save();
                        $this->save();
                    });
                    AppLog::info('Target Orders adjusted', null, $this->comm_profile);
                    return true;
                } catch (Exception $e) {
                    report($e);
                    AppLog::error("Can't adjust target order", $e->getMessage(), $this->comm_profile);
                    return false;
                }
            }
            if ($target->id == $this->id) {
                $swap = true;
            }
        }
        return true;
    }

    public function deleteTarget()
    {
        try {
            $this->delete();
            AppLog::info('Comm Profile target deleted', loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't delete Comm Profile target", loggable: $this);
            return false;
        }
    }

    ///scopes 
    public function scopeTodaysTarget()
    {
        $now = Carbon::now();
        // return $this->
    }

    ///relations
    public function comm_profile(): BelongsTo
    {
        return $this->belongsTo(CommProfile::class);
    }
}
