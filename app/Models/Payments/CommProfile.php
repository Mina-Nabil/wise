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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        'title',
        'type',
        'per_policy',
        'desc',
        'comm_profile_id',
        'user_id',
        'balance',
        'unapproved_balance',
        'select_available',
        'auto_override_id' //Available for Selection
    ];

    ///static functions
    public static function newCommProfile(
        $type, //enum one of TYPES
        bool $per_policy, //switch
        $user_id = null, //can be linked to user
        string $title = null, //can be null if a user is selected 
        string $desc = null,
        bool $select_available = false, //switch,
        $auto_override_id = null, //can be linked to user
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
                "select_available"  =>  $select_available,
                "user_id"       =>  $user_id,
                "title"         =>  $formattedTitle,
                "auto_override_id"  =>  $auto_override_id,
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

    public static function getTotalCommsPaid($profile_id)
    {
        return DB::table('comm_payments_details')
            ->selectRaw('SUM("comm_payments_details.amount") as paid_amount')
            ->join('comm_payments_details', 'sales_comms.id', '=', 'comm_payments_details.sales_comm_id')
            ->join('comm_profiles', 'comm_profiles.id', '=', 'sales_comms.comm_profile_id')
            ->where('sales_comms.comm_profile_id', '=', $profile_id)
            ->where('comm_payments_details.paid_percentage', '>', 0)
            ->first()->paid_amount;
    }


    ///model functions
    public function downloadAccountStatement(Carbon $start, Carbon $end)
    {
        $comms = $this->sales_comm()->bySoldPoliciesStartEnd($start, $end)
            ->with('sold_policy', 'sold_policy.client')
            ->get();
        $template = IOFactory::load(resource_path('import/account_statement.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 2;
        foreach ($comms as $comm) {
            $activeSheet->getCell('A' . $i)->setValue($comm->sold_policy->is_renewed ? 'تجديد' : 'اصدار');
            $activeSheet->getCell('B' . $i)->setValue($comm->sold_policy->policy_number);
            $activeSheet->getCell('C' . $i)->setValue($comm->sold_policy->client->name);

            $activeSheet->getCell('D' . $i)->setValue((new Carbon($comm->sold_policy->start))->format('d-M-y'));
            $activeSheet->getCell('E' . $i)->setValue($comm->sold_policy->net_premium);
            $activeSheet->getCell('F' . $i)->setValue($comm->sold_policy->gross_premium);
            $activeSheet->getCell('G' . $i)->setValue($comm->amount);
            $activeSheet->getCell('H' . $i)->setValue($comm->sold_policy->is_renewed ? 'تجديد' :  round($comm->sold_policy->insured_value * 0.0005, 3, PHP_ROUND_HALF_DOWN));
            $activeSheet->getCell('I' . $i)->setValue($comm->sold_policy->insured_value);

            $activeSheet->insertNewRowBefore($i);
        }


        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . "profile_balance{$this->id}.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public function startManualTargetsRun(Carbon $end_date)
    {
        $this->load('targets');
        /** @var Target */
        foreach ($this->targets as $t) {
            $t->processTargetPayments($end_date, true);
        }
        return true;
    }

    public function getValidDirectCommissionConf(OfferOption|Policy $option): CommProfileConf|false
    {
        $this->load('configurations');
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
        $auto_override_id = null,
    ) {
        try {
            $this->update([
                "type"          =>  $type,
                "per_policy"    =>  $per_policy,
                "select_available"    =>  $select_available,
                "auto_override_id"  =>  $auto_override_id,
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

    public function refreshBalances()
    {
        try {
            // $total_comms = $this->sales_comm()->notCancelled()
            // ->selectRaw('SUM("amount") as total_paid')->first()->total_paid;
            $total_balance_comms = 0;
            $total_unapproved_comms = 0;
            foreach ($this->sales_comm as  $comm) {
                if ($comm->is_direct) {
                    $total_balance_comms += (min($comm->company_paid_percent / 100, 1) * $comm->amount);
                    $total_unapproved_comms += (min($comm->client_paid_percent / 100, 1) * $comm->amount);
                } else {
                    $total_balance_comms += $comm->amount;
                }
            }

            $total_paid =  $this->payments()->notCancelled()->selectRaw('SUM(amount) as total_paid')->first()->total_paid;
            Log::info("Total paid: " . $total_paid);
            Log::info("Total Comms: " . $total_balance_comms);
            $this->update([
                "balance" => $total_balance_comms - $total_paid,
                "unapproved_balance" => max($total_unapproved_comms - $total_paid, 0),
            ]);
            AppLog::info("Updating comm profile balance",  loggable: $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't update comm profile balance", desc: $e->getMessage(), loggable: $this);
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

    public function getPaidSoldPolicies(Carbon $from, Carbon $to)
    {
        return $this->sold_policies()
            ->select('sold_policies.*')
            ->selectRaw('SUM(client_payments.amount) as client_paid_by_dates')
            ->join('client_payments', 'client_payments.sold_policy_id', 'sold_policies.id')
            ->whereIn('client_payments.status', ClientPayment::PYMT_PAID_STATES)
            ->whereBetween('client_payments.payment_date', [
                $from->format('Y-m-d 00:00:00'),
                $to->format('Y-m-d 23:59:00')
            ])->groupBy('sold_policies.id')
            ->get();
    }

    public function addTarget(
        $day_of_month,
        $each_month,
        $prem_target,
        $min_income_target,
        $comm_percentage,
        $add_to_balance = 100,
        $add_as_payment = 100,
        $base_payment = null,
        $max_income_target = null,
        Carbon $next_run_date = null,
        $is_end_of_month = false,
        $is_full_amount = false,
    ) {
        try {
            AppLog::info("Creating comm profile target", loggable: $this);
            $order = $this->targets()->count() + 1;
            $target = $this->targets()->create([
                "day_of_month"      =>  $day_of_month,
                "each_month"        =>  $each_month,
                "prem_target"       =>  $prem_target,
                "comm_percentage"   =>  $comm_percentage,
                "min_income_target" =>  $min_income_target,
                "add_to_balance"    =>  $add_to_balance,
                "add_as_payment"    =>  $add_as_payment,
                "base_payment"      =>  $base_payment,
                "max_income_target" =>  $max_income_target,
                "is_end_of_month"   =>  $is_end_of_month,
                "is_full_amount"   =>  $is_full_amount ?? false,
                "next_run_date"     =>  $next_run_date?->format('Y-m-d') ?? null,
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

    /**
     * @param array $linked_sales_comms [
     *  $sales_comm_id => [ 'paid_percentage' => $perct , "amount" => $amount  ]
     * ]
     * Use scope notTotalyPaid in SalesComm
     */
    public function addPayment(
        $amount,
        $type,
        $doc_url = null,
        $note = null,
        $must_add = false,
        $linked_sales_comms = []
    ) {
        if ($amount <= $this->balance)
            $needs_approval = false;
        else
            $needs_approval = true;

        try {
            /** @var CommProfilePayment */
            $payment = $this->payments()->create([
                "creator_id"    =>  Auth::id(),
                "amount"    =>  $amount,
                "type"      =>  $type,
                "doc_url"   =>  $doc_url,
                "needs_approval"   =>  $needs_approval,
                "note"      =>  $note
            ]);
            if ($payment->save()) {
                $payment->sales_commissions()->sync($linked_sales_comms);
                $this->refreshBalances();
            }
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

    public function deleteProfile()
    {
        try {
            DB::transaction(function () {
                $this->sales_comm()->delete();
                $this->configurations()->delete();
                $this->targets()->delete();
                $this->payments()->delete();
                $this->client_payments()->update([
                    'sales_out_id'  =>  null
                ]);
                $this->delete();
            });
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
    public function scopeOverride($query)
    {
        return $query->where('type', self::TYPE_OVERRIDE);
    }
    public function scopeLinkedToSoldPolicy($query, $sold_policy_id)
    {
        return $query->select('comm_profiles.*')
            ->join('sales_comms', 'sales_comms.comm_profile_id', '=', 'comm_profiles.id')
            ->where('sales_comms.sold_policy_id', $sold_policy_id);
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
