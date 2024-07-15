<?php

namespace App\Models\Payments;

use App\Models\Business\SoldPolicy;
use App\Models\Insurance\Company;
use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use App\Models\Offers\OfferOption;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommProfile extends Model
{
    const FILES_DIRECTORY = 'comm_profile/payments/';
    const MORPH_TYPE = 'comm_profile';
    use HasFactory;

    const TYPE_SALES_IN = 'sales_in';
    const TYPE_SALES_OUT = 'sales_out';
    const TYPE_OVERRIDE = 'override';

    const TYPES = [
        self::TYPE_SALES_IN,
        self::TYPE_SALES_OUT,
        self::TYPE_OVERRIDE,
    ];

    protected $fillable = [
        'title', 'type', 'per_policy', 'desc', 'comm_profile_id',
        'user_id', 'balance', 'unapproved_balance',
        'select_available' //Available for Selection
    ];

    ///static functions
    public static function newCommProfile(
        $type, //enum one of TYPES
        bool $per_policy, //switch
        $user_id = null, //can be linked to user
        string $title = null, //can be null if a user is selected 
        string $desc = null,
        bool $select_available = false, //switch
    ): self|bool {
        assert($user_id !== null || $title != null, "Must include a title or select a user");
        try {
            $formattedTitle = $title;
            if ($user_id) {
                $user = User::findOrFail($user_id);
                $formattedTitle = "$user->username's $type";
            }
            $newComm = new self([
                "type"          =>  $type,
                "per_policy"    =>  $per_policy,
                "select_available"    =>  $select_available,
                "user_id"       =>  $user_id,
                "title"         =>  $formattedTitle,
                "desc"          =>  $desc,
            ]);
            AppLog::error("creating new comm profile");
            $newComm->save();
            return $newComm;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create comm profile", desc: $e->getMessage());
            return false;
        }
    }

    ///model functions
    public function generateCommissionFromTargets()
    {
    }

    public function getValidCommissionConf(OfferOption $option): CommProfileConf|false
    {
        $option->loadMissing('policy');
        $this->loadMissing('configurations');
        foreach ($this->configurations as $conf) {
            if ($conf->matches($option)) {
                return $conf;
            }
        }
        return false;
    }

    public function editProfile(
        $type,
        bool $per_policy,
        string $title = null,
        string $desc = null,
        bool $select_available = false, //switch
    ) {
        try {
            $this->update([
                "type"          =>  $type,
                "per_policy"    =>  $per_policy,
                "select_available"    =>  $select_available,
                "title"         =>  $title,
                "desc"          =>  $desc,
            ]);
            AppLog::info("Edited comm profile",  loggable: $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit comm profile", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function updateBalance(
        float $amount
    ) {
        try {
            $this->increment(
                "balance",
                $amount
            );
            AppLog::info("Updating comm profile balance",  loggable: $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't update comm profile balance", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function updateUnapprovedBalance(
        float $amount
    ) {
        try {
            $this->increment(
                "unapproved_balance",
                $amount
            );
            AppLog::info("Updating comm profile unapproved balance",  loggable: $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't update comm profile unapproved balance", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function addConfiguration(
        $percentage, //commission percentage
        $from, //from const FROMS
        Policy|Company $condition = null, //include a policy or a company as a condition
        $line_of_business = null // or select a line of business as the condition - line of business is on of Policy::LINES_OF_BUSINESS
    ) {
        assert(
            (!$condition && !$line_of_business) ||
                ($condition && !$line_of_business) ||
                (!$condition && $line_of_business),
            "Must either a condition or a line of business or both empty"
        );

        try {
            AppLog::info("Creating comm profile configuration", loggable: $this);
            $order = $this->configurations()->count() + 1;
            $conf = $this->configurations()->create([
                "percentage"    =>  $percentage,
                "from"          =>  $from,
                "line_of_business" =>  $line_of_business,
                "order"         =>  $order
            ]);
            if ($condition)
                $conf->condition()->associate($condition);
            $conf->save();
            return $conf;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create comm profile conf", desc: $e->getMessage());
            return false;
        }
    }

    public function getSoldPolicies(Carbon $from, Carbon $to)
    {
        return $this->sold_policies()->whereBetween('created_at', [
            $from->format('Y-m-d 00:00:00'),
            $to->format('Y-m-d 23:59:00')
        ])->get();
    }

    /** Must be called from daily job */
    public function checkFulfilledTargets()
    {

        foreach ($this->targets()->get() as $target) {
            switch ($target->period) {
                case Target::PERIOD_MONTH:
                    $month_ini = new Carbon("first day of last month");
                    $month_end = new Carbon("last day of last month");

                    $soldPolicies = $this->getSoldPolicies($month_ini, $month_end);
                    $totalNet = $soldPolicies->sum('net_premium');
                    $totalIncome = $soldPolicies->sum('total_policy_comm');
                    if($this->prem_target <= $totalNet && $this->income_target <= $totalIncome){
                        $target->addTargetPayments($soldPolicies);
                    }
                    break;
                case Target::PERIOD_QUARTER:
                    # code...
                    break;
                case Target::PERIOD_YEAR:
                    # code...
                    break;
                case Target::PERIOD_YEAR_TO_DATE:
                    # code...
                    break;

                default:
                    return false;
            }
        }
    }


    public function addTarget(
        $period, //from const PERIODS
        $prem_target, //target net prem amount the sales should surpass
        $income_target, //target net commission amount the sales should surpass
        $comm_percentage
    ) {
        try {
            AppLog::info("Creating comm profile target", loggable: $this);
            $order = $this->targets()->count() + 1;
            $target = $this->targets()->create([
                "period"            =>  $period,
                "prem_target"       =>  $prem_target,
                "comm_percentage"   =>  $comm_percentage,
                "income_target"     =>  $income_target,
                "order"             =>  $order
            ]);
            $target->save();
            return $target;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create comm profile target", desc: $e->getMessage());
            return false;
        }
    }

    public function addPayment(
        $amount,
        $type,
        $doc_url = null,
        $note = null
    ) {
        if ($amount < $this->balance)
            $needs_approval = false;
        else if ($amount < ($this->balance + $this->unapproved_balance))
            $needs_approval = true;
        else throw new Exception("Amount is more than the available balance");
        try {

            $payment = $this->payments()->create([
                "creator_id"    =>  Auth::id(),
                "amount"    =>  $amount,
                "type"      =>  $type,
                "doc_url"   =>  $doc_url,
                "needs_approval"   =>  $needs_approval,
                "note"      =>  $note
            ]);
            $payment->save();
            if ($payment->needs_approval) {
                /** @var User */
                $remon = User::find(1);
                $remon->pushNotification("Payment pending approval", $this->title . " has a new 'pending approval' payment", "commissions/" . $this->id);
            }
            return $payment;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create comm profile payment", desc: $e->getMessage());
            return false;
        }
    }

    public function addTargetCycle($day_of_month, $each_month)
    {
        assert($each_month == 3 || $each_month == 4 || $each_month == 6 ||  $each_month == 12, "Please set target cycle to 3, 4, 6 or 12");
        try {
            $this->target_cycles()->create([
                "day_of_month"  =>  $day_of_month,
                "each_month"    =>  $each_month,
            ]);
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ///attributes
    public function getIsSalesOutAttribute()
    {
        return $this->type == self::TYPE_SALES_OUT;
    }

    ///scopes
    public function scopeSearchBy($query, $text)
    {
        return $query->where('title', 'LIKE', "%$text%");
    }
    public function scopeSalesIn($query)
    {
        return $query->where('type', self::TYPE_SALES_IN);
    }
    public function scopeSalesOut($query)
    {
        return $query->where('type', self::TYPE_SALES_OUT);
    }

    ///relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sales_comm(): HasMany
    {
        return $this->hasMany(SalesComm::class);
    }

    public function configurations(): HasMany
    {
        return $this->hasMany(CommProfileConf::class);
    }

    public function target_cycles(): HasMany
    {
        return $this->hasMany(TargetCycle::class);
    }

    public function targets(): HasMany
    {
        return $this->hasMany(Target::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CommProfilePayment::class);
    }

    public function client_payments(): HasMany
    {
        return $this->hasMany(ClientPayment::class, 'sales_out_id');
    }

    public function sold_policies(): BelongsToMany
    {
        return $this->belongsToMany(SoldPolicy::class, 'sales_comms');
    }

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, "offer_comm_profiles");
    }
}
