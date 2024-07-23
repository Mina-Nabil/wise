<?php

namespace App\Models\Payments;

use App\Models\Users\AppLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Target extends Model
{
    const MORPH_TYPE = 'target';

    use HasFactory;

    public $fillable = [
        "base_payment", "prem_target", "comm_percentage", "order", "min_income_target", "max_income_target",
        "day_of_month", "each_month", "add_to_balance", "add_as_payment"
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
    /** Should be called periodically to generate payments */
    public function addTargetPayments(Carbon $end_date = null)
    {
        $end_date = $end_date ?? Carbon::now();
        $start_date = $end_date->clone()->subMonths($this->each_month);
    }

    /** Should be called periodically to check performance */
    public function isTargetAchieved(Carbon $end_date = null)
    {
        $end_date = $end_date ?? Carbon::now();
        $start_date = $end_date->clone()->subMonths($this->each_month);
        $soldPolicies = $this->comm_profile->sold_policies()->whereBetween('created_at', [
            $start_date->format('Y-m-d'),
            $end_date->format('Y-m-d'),
        ]);
        $totalIncome = $soldPolicies->sum('total_policy_comm');
        return $totalIncome >= $this->max_income_target;
    }

    public function editInfo(
        $day_of_month,
        $each_month,
        $prem_target,
        $min_income_target,
        $comm_percentage,
        $add_to_balance = null,
        $add_as_payment = null,
        $base_payment = null,
        $max_income_target = null,
    ) {
        try {
            AppLog::info("Updating comm profile target", loggable: $this);
            $this->update([
                "day_of_month"  =>  $day_of_month,
                "each_month"    =>  $each_month,
                "base_payment"  =>  $base_payment,
                "comm_percentage"   =>  $comm_percentage,
                "min_income_target" =>  $min_income_target,
                "prem_target"   =>  $prem_target,
                "add_to_balance"   =>  $add_to_balance,
                "add_as_payment"   =>  $add_as_payment,
                "max_income_target"   =>  $max_income_target,
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

    ///attributes
    public function getNextRunDateAttribute()
    {
        $this->loadMissing('runs');
        $last_run = $this->runs->first();
        $now = Carbon::now();
        return
            $last_run ?
            (new Carbon($last_run->created_at))->addMonths($this->each_month)->setDay($this->day_of_month) : ($now->day < $this->day_of_month ?
                $now->setDay($this->day_of_month) :
                $now->addMonth()->setDay($this->day_of_month));
    }

    public function getLastRunDateAttribute()
    {
        $this->loadMissing('runs');
        $last_run = $this->runs->first();
        return $last_run ? new Carbon($last_run->created_at) : null;
    }

    public function getIsDueAttribute()
    {
        return $this->next_run_date->isToday();
    }


    ///scopes 
    public function scopeOnlyToday($query)
    {
        $now = Carbon::now();
        return $query->where('day_of_month', $now->day_of_month);
    }

    ///relations
    public function comm_profile(): BelongsTo
    {
        return $this->belongsTo(CommProfile::class);
    }

    public function runs(): HasMany
    {
        return $this->hasMany(TargetRun::class)->latest();
    }
}
