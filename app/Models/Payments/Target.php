<?php

namespace App\Models\Payments;

use App\Models\Business\SoldPolicy;
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
        "base_payment",
        "prem_target",
        "comm_percentage",
        "order",
        "min_income_target",
        "max_income_target",
        "day_of_month",
        "each_month",
        "next_run_date",
        "is_end_of_month",
        "renewal_percentage", //percentage of the target to be paid for renewal policies only
        "sales_out_percentage" //percentage of the target to be paid for sales out policies only
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
    /** Effective commission percentage (0-1) for a sold policy, applying renewal/sales-out multipliers.
     * Used by CommProfile::processTargetPayments while allocating income across target brackets. */
    public function effectivePercentage(SoldPolicy $sp): float
    {
        $commPercentage = $this->comm_percentage / 100;
        $commPercentage *= ($sp->renewal_policy_id && $this->renewal_percentage > 0) ? ($this->renewal_percentage / 100) : 1;
        if ($sp->has_sales_out && $this->sales_out_percentage > 0) {
            $commPercentage *= ($this->sales_out_percentage / 100);
        }
        return $commPercentage;
    }

    public function addRun($added_to_balance, $added_to_payments)
    {
        try {
            $this->runs()->create([
                "added_to_balance"      => $added_to_balance,
                "added_to_payments"     => $added_to_payments
            ]);
        } catch (Exception $e) {
            report($e);
        }
    }

    public function editInfo(
        $day_of_month,
        $each_month,
        $prem_target,
        $min_income_target,
        $comm_percentage,
        $base_payment = null,
        $max_income_target = null,
        $next_run_date = null,
        $is_end_of_month = false,
        $renewal_percentage = null,
        $sales_out_percentage = null,
    ) {
        try {
            AppLog::info("Updating comm profile target", loggable: $this);
            $updates = [
                "day_of_month"  =>  $day_of_month,
                "each_month"    =>  $each_month,
                "base_payment"  =>  $base_payment,
                "comm_percentage"   =>  $comm_percentage,
                "min_income_target" =>  $min_income_target,
                "prem_target"   =>  $prem_target,
                "max_income_target"   =>  $max_income_target,
                "is_end_of_month"   =>  $is_end_of_month,
                "renewal_percentage"   =>  $renewal_percentage,
                "sales_out_percentage"   =>  $sales_out_percentage,
            ];
            if ($next_run_date)
                $updates['next_run_date'] = $next_run_date->format('Y-m-d');
            $this->update($updates);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't update comm profile target", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function moveUp()
    {
        $this->load('comm_profile', 'comm_profile.targets');
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
        $this->load('comm_profile', 'comm_profile.targets');
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
    public function getCalculatedNextRunDateAttribute()
    {
        if ($this->next_run_date && (new Carbon($this->next_run_date))->isFuture()) return new Carbon($this->next_run_date);
        $this->load('runs');
        $last_run = $this->runs->first();
        $now = Carbon::now();
        $run_date = $last_run ?
            (new Carbon($last_run->created_at))->addMonths($this->each_month)->setDay($this->day_of_month) : ($now->day < $this->day_of_month ?
                $now->setDay($this->day_of_month) :
                $now->addMonth()->setDay($this->day_of_month));
        return $this->is_end_of_month ? $run_date->setDay($run_date->format('t')) : $run_date;
    }

    public function getLastRunDateAttribute()
    {
        $this->load('runs');
        $last_run = $this->runs->first();
        return $last_run ? new Carbon($last_run->created_at) : null;
    }

    public function getIsDueAttribute()
    {
        return $this->calculated_next_run_date->isToday();
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
