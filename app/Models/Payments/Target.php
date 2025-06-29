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
use Illuminate\Support\Facades\Log;

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
        "add_to_balance",
        "add_as_payment",
        "next_run_date",
        "is_end_of_month",
        "is_full_amount"
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
    /** Should be called periodically to check target. It will check if target if acheived. 
     * If yes it will update the related sales commissions */
    public function processTargetPayments(?Carbon $end_date = null, $is_manual = false)
    {
        $this->load('comm_profile');
        $end_date = $end_date ? $end_date->setTime(0, 0, 1) : Carbon::now()->setTime(0, 0, 1);
        $start_date = $end_date->clone()->subMonths($this->each_month);
        $soldPolicies = $this->comm_profile->getPaidSoldPolicies($start_date, $end_date);
        $totalIncome = 0;
        $linkedComms = [];  //$sales_comm_id => [ 'paid_percentage' => $perct , "amount" => $amount  ]
        $paidAmountsPercent = [];

        /** @var SoldPolicy */
        foreach ($soldPolicies as $sp) {
            $sp->generatePolicyCommissions();
            $sp->calculateTotalSalesOutComm();
            $tmpAmount = ($sp->after_tax_comm *
                ($sp->client_paid_by_dates / $sp->gross_premium)) - $sp->total_comm_subtractions;
            $totalIncome += $tmpAmount;
        }
        foreach ($soldPolicies as $sp) {
            $paidAmountsPercent[$sp->id] = (($sp->after_tax_comm *
                ($sp->client_paid_by_dates / $sp->gross_premium)) - $sp->total_comm_subtractions) / $totalIncome;
        }
        //return false if the target is not acheived
        if ($totalIncome < $this->min_income_target) return false;

        $balance_update = ($this->comm_percentage / 100) *
            (($this->is_full_amount ? $totalIncome :
                min(
                    $totalIncome,
                    ($this->max_income_target ?? $totalIncome)
                )) - $this->min_income_target) *  ($this->add_to_balance / 100);

        $original_payment = (($this->add_as_payment / 100) * $balance_update);

        $payment_to_add = max($this->base_payment, $original_payment);

        DB::transaction(function () use ($soldPolicies, $balance_update, $payment_to_add, $is_manual, $paidAmountsPercent, &$linkedComms, $original_payment) {
            $salesCommissions = SalesComm::getBySoldPoliciesIDs($this->comm_profile->id, $soldPolicies->pluck('id')->toArray());

            /** @var SalesComm */
            foreach ($salesCommissions as $s) {
                $s->updatePaymentByTarget($this, $original_payment * $paidAmountsPercent[$s->sold_policy_id], $is_manual);
                if ($s->amount > 0)
                    $linkedComms[$s->id] = [
                        'paid_percentage'   => (($original_payment * $paidAmountsPercent[$s->sold_policy_id]) / $s->amount) * 100,
                        'amount'            =>  $original_payment * $paidAmountsPercent[$s->sold_policy_id]
                    ];
            }

            if ($balance_update)
                $this->comm_profile->refreshBalances();

            if ($payment_to_add)
                $this->comm_profile->addPayment($payment_to_add, CommProfilePayment::PYMT_TYPE_BANK_TRNSFR, note: "Target#$this->id payment", must_add: true, linked_sales_comms: $linkedComms);

            if ($payment_to_add > $original_payment) {
                $this->comm_profile->addPayment($original_payment - $payment_to_add, CommProfilePayment::PYMT_TYPE_BANK_TRNSFR, note: "Target#$this->id difference", must_add: true, linked_sales_comms: $linkedComms);
            }

            $this->addRun($balance_update - $payment_to_add, $payment_to_add);
        });
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
        $add_to_balance = null,
        $add_as_payment = null,
        $base_payment = null,
        $max_income_target = null,
        $next_run_date = null,
        $is_end_of_month = false,
        $is_full_amount = false,
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
                "add_to_balance"   =>  $add_to_balance,
                "add_as_payment"   =>  $add_as_payment,
                "max_income_target"   =>  $max_income_target,
                "is_end_of_month"   =>  $is_end_of_month,
                "is_full_amount"   =>  $is_full_amount ?? false,
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
