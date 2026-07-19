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
        'select_available', //Available for Selection
        'available_for_id',
        'auto_override_id',
        'account_id'
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
        $available_for_id = null, //can be linked to user
        $account_id = null, //can be linked to an account
    ): self|bool {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('create', self::class)) return false;
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
                "available_for_id"  =>  $available_for_id,
                "desc"          =>  $desc,
                "account_id"    =>  $account_id,
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
            ->with(
                'sold_policy',
                'sold_policy.client',
                'sold_policy.policy.company',
                'sold_policy.customer_car.car.car_model.brand',
                'sold_policy.sales_comms.comm_profile'
            )
            ->notCancelled()
            // ->notPaid()
            ->notPolicyCancelled()
            ->notPolicyExpired()
            ->get();
        $template = IOFactory::load(resource_path('import/account_statement.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 3;
        foreach ($comms as $comm) {
            $clientPayment = $comm->sold_policy->client_payments->first();
            $salesOutNames = $comm->sold_policy->sales_comms
                ->filter(fn ($sc) => $sc->comm_profile?->is_sales_out)
                ->pluck('comm_profile.title')
                ->filter()
                ->unique()
                ->implode(', ');

            // Insert the row before writing to it (rather than after) so rows land in
            // natural top-to-bottom order. insertNewRowBefore() does not reliably carry the
            // bordered row style onto the fresh row, so it's applied explicitly below instead.
            $activeSheet->insertNewRowBefore($i);
            $activeSheet->getStyle('A' . $i . ':W' . $i)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

            $activeSheet->getCell('A' . $i)->setValue($comm->sold_policy->offer?->is_renewal ? 'تجديد' : 'اصدار');
            $activeSheet->getCell('B' . $i)->setValue($comm->sold_policy->policy?->company?->name . '-' . $comm->sold_policy->policy?->name);
            $activeSheet->getCell('C' . $i)->setValue($comm->sold_policy->policy_number);
            $activeSheet->getCell('D' . $i)->setValue($comm->sold_policy->client?->full_name);
            $activeSheet->getCell('E' . $i)->setValue($clientPayment?->type  . ' / ' .  $clientPayment?->status);
            $activeSheet->getCell('F' . $i)->setValue((new Carbon($comm->sold_policy->start))->format('d-M-y'));
            $activeSheet->getCell('G' . $i)->setValue($comm->sold_policy->net_premium);
            $activeSheet->getCell('H' . $i)->setValue($comm->sold_policy->gross_premium);
            $activeSheet->getCell('I' . $i)->setValue($comm->amount);
            $activeSheet->getCell('J' . $i)->setValue($this->is_sales_out ?
                ($comm->sold_policy->offer?->is_renewal ? 'تجديد' :  round($comm->sold_policy->insured_value * 0.0005, 3, PHP_ROUND_HALF_DOWN)) : '-');
            $activeSheet->getCell('K' . $i)->setValue($comm->sold_policy->insured_value);
            $activeSheet->getCell('M' . $i)->setValue($comm->sold_policy->customer_car?->car?->car_model?->brand?->name . ' - ' . $comm->sold_policy->customer_car?->car?->car_model?->name);
            $activeSheet->getCell('N' . $i)->setValue($comm->status);
            $activeSheet->getCell('O' . $i)->setValue($salesOutNames);
            $activeSheet->getCell('P' . $i)->setValue($comm->sold_policy->total_policy_comm);
            $activeSheet->getCell('Q' . $i)->setValue($comm->sold_policy->total_policy_comm * .95);
            $activeSheet->getCell('R' . $i)->setValue($comm->sold_policy->sales_out_comm);
            $activeSheet->getCell('S' . $i)->setValue($comm->sold_policy->discount);
            $activeSheet->getCell('T' . $i)->setValue($comm->amount - $comm->sold_policy->discount);
            $activeSheet->getCell('U' . $i)->setValue($comm->comm_percentage);
            $activeSheet->getCell('V' . $i)->setValue($comm->sold_policy->total_policy_comm - $comm->sold_policy->total_comm_subtractions);

            $i++;
        }

        // Totals row: sums for Gross Premium / عمولة معرض / عمولة بائع, a net figure, and a label.
        if ($comms->isNotEmpty()) {
            $firstDataRow = 3;
            $lastDataRow = $i - 1;
            $activeSheet->getCell('H' . $i)->setValue("=SUM(H{$firstDataRow}:H{$lastDataRow})");
            $activeSheet->getCell('I' . $i)->setValue("=SUM(I{$firstDataRow}:I{$lastDataRow})");
            $activeSheet->getCell('J' . $i)->setValue("=SUM(J{$firstDataRow}:J{$lastDataRow})");
            $activeSheet->getCell('K' . $i)->setValue("=H{$i}-I{$i}-J{$i}");
            $activeSheet->getCell('L' . $i)->setValue('صافي التحصيل');
        }

        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . "profile_balance_{$this->title}.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public function startManualTargetsRun(Carbon $end_date)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        return $this->processTargetPayments($end_date, true);
    }

    /** Should be called periodically (once per profile) to check the profile's targets.
     * Targets act as marginal income brackets ordered by min_income_target: each target's
     * percentage is applied only to the income portion falling inside its bracket.
     * Achieved portions are stored as sub sales comms (and comm_target_runs) per sales comm. */
    public function processTargetPayments(?Carbon $end_date = null, $is_manual = false)
    {
        $targets = $this->targets()->reorder()->orderBy('min_income_target')->get();
        if ($targets->isEmpty()) return false;

        /** @var Target */
        $firstTarget = $targets->first();
        if ($targets->pluck('each_month')->unique()->count() > 1)
            AppLog::warning("Profile targets have different each_month values, using first target's", loggable: $this);

        $end_date = $end_date ? $end_date->setTime(0, 0, 1) : Carbon::now()->setTime(0, 0, 1);
        $start_date = $end_date->clone()->subMonths($firstTarget->each_month)->setTime(0, 0, 0);
        $end_date = $end_date->clone()->subDay()->setTime(23, 59, 59);

        // deterministic order so the bracket-straddling policy is stable across re-runs
        $soldPolicies = $this->getPaidSoldPolicies($start_date, $end_date)->sortBy('id')->values();
        if ($soldPolicies->isEmpty()) return false;

        //per-policy raw wise income (percentage-independent)
        $incomes = []; //sold_policy_id => income
        $totalIncome = 0;
        /** @var SoldPolicy */
        foreach ($soldPolicies as $sp) {
            $sp->generatePolicyCommissions();
            $sp->calculateTotalSalesOutComm();
            $totalClientPaidBetween = $sp->getTotalClientPaidBetween($start_date, $end_date);
            if ($totalClientPaidBetween < $sp->total_client_paid) {
                $incomes[$sp->id] = $sp->calculateSalesCommissionForCertainAmount($totalClientPaidBetween) * .95;
            } else {
                $incomes[$sp->id] = ($sp->tax_amount > 0 ? $sp->after_tax_comm : ($sp->after_tax_comm * .95)) - $sp->total_comm_subtractions;
            }
            $totalIncome += $incomes[$sp->id];
        }

        if ($totalIncome <= 0) return false;
        //return false if the lowest target is not acheived
        if ($totalIncome < $firstTarget->min_income_target) return false;

        //allocate each policy's income sequentially into the target brackets
        $portions = []; //sold_policy_id => [target_id => ['amount','percentage','title']]
        $bracketPayouts = []; //target_id => payout
        $cumulative = 0;
        foreach ($soldPolicies as $sp) {
            $policyStart = $cumulative;
            $policyEnd = $cumulative + $incomes[$sp->id];
            $cumulative = $policyEnd;

            /** @var Target */
            foreach ($targets as $t) {
                $bracketStart = $t->min_income_target;
                $bracketEnd = $t->max_income_target > 0 ? $t->max_income_target : INF;
                $portionIncome = min($policyEnd, $bracketEnd) - max($policyStart, $bracketStart);
                if ($portionIncome <= 0) continue;

                $commPercentage = $t->effectivePercentage($sp);
                $portionAmount = $portionIncome * $commPercentage;
                $portions[$sp->id][$t->id] = [
                    'amount'        =>  $portionAmount,
                    'percentage'    =>  $commPercentage * 100,
                    'title'         =>  "Target#$t->id (" . number_format($bracketStart) . " - " . ($bracketEnd === INF ? "∞" : number_format($bracketEnd)) . ")",
                ];
                $bracketPayouts[$t->id] = ($bracketPayouts[$t->id] ?? 0) + $portionAmount;
            }
        }
        $totalPayout = array_sum($bracketPayouts);

        //base_payment floor: strongest guarantee among achieved targets
        $achievedBasePayment = $targets
            ->filter(fn($t) => $totalIncome > $t->min_income_target)
            ->max('base_payment') ?? 0;
        $payment_to_add = max($achievedBasePayment, $totalPayout);

        Log::info("Profile#$this->id targets run", ["totalIncome" => $totalIncome, "totalPayout" => $totalPayout, "payment_to_add" => $payment_to_add]);

        DB::transaction(function () use ($soldPolicies, $targets, $portions, $bracketPayouts, $totalPayout, $payment_to_add, $end_date) {
            $linkedComms = [];  //$sales_comm_id => [ 'paid_percentage' => $perct , "amount" => $amount  ]
            $salesCommissions = SalesComm::getBySoldPoliciesIDs($this->id, $soldPolicies->pluck('id')->toArray());

            /** @var SalesComm */
            foreach ($salesCommissions as $s) {
                $policyPortions = $portions[$s->sold_policy_id] ?? [];
                $s->applyTargetPortions($policyPortions);
                $commTotal = array_sum(array_column($policyPortions, 'amount'));
                if ($s->amount > 0 && $commTotal > 0)
                    $linkedComms[$s->id] = [
                        'paid_percentage'   =>  ($commTotal / $s->amount) * 100,
                        'amount'            =>  $commTotal
                    ];
            }

            $this->refreshBalances();

            if ($payment_to_add)
                $this->addPayment($payment_to_add, CommProfilePayment::PYMT_TYPE_BANK_TRNSFR, note: "Profile#$this->id targets run", must_add: true, linked_sales_comms: $linkedComms, target_date: $end_date);

            if ($payment_to_add > $totalPayout)
                $this->addPayment($totalPayout - $payment_to_add, CommProfilePayment::PYMT_TYPE_BANK_TRNSFR, note: "Profile#$this->id targets run difference", must_add: true, linked_sales_comms: $linkedComms, target_date: $end_date);

            // every target advances together (TargetRun.created_at drives the next run date),
            // so brackets that earned nothing this run still record a zero-amount run
            foreach ($targets as $t)
                $t->addRun(0, $bracketPayouts[$t->id] ?? 0);
        });
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

    public function editProfile($type, $per_policy, $title = null, $desc = null, $select_available = false, $auto_override_id = null, $available_for_id = null, $account_id = null, $user_id = null)
    {
        // The blank "None" option on these selects binds as an empty string, not null;
        // normalize so nullable foreign keys are actually set to NULL instead of ''.
        $user_id = $user_id !== '' ? $user_id : null;
        $auto_override_id = $auto_override_id !== '' ? $auto_override_id : null;
        $available_for_id = $available_for_id !== '' ? $available_for_id : null;
        $account_id = $account_id !== '' ? $account_id : null;

        try {
            $originalUserId = $this->user_id;
            $this->user_id = $user_id;

            // When the linked user changes, keep the auto-generated title convention in sync
            if ($user_id && $user_id != $originalUserId) {
                $linkedUser = User::find($user_id);
                if ($linkedUser) {
                    $title = "{$linkedUser->username}'s {$type}";
                }
            }

            if ($this->user_id && !$title) {
                $title = $this->title;
            }
            $this->type = $type;
            $this->per_policy = $per_policy;
            $this->title = $title;
            $this->desc = $desc;
            $this->select_available = $select_available;
            $this->auto_override_id = $auto_override_id;
            $this->available_for_id = $available_for_id;
            $this->account_id = $account_id;
            
            $res = $this->save();
            if (!$res) {
                throw new \Exception("Error editing commission profile", 1);
            }
            
            if (env('IS_API')) {
                Log::write('info', "Profile updated by api");
            } else {
                Log::write('info', "Profile {$this->id} updated by " . auth()->user()->email);
            }
            
            return true;
        } catch (\Exception $e) {
            Log::write('error', $e->getMessage() . $e->getTraceAsString());
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
        $line_of_business = null, // or select a line of business as the condition - line of business is on of Policy::LINES_OF_BUSINESS
        $renewal_percentage = null, // percentage for renewal policies
        $sales_out_percentage = null // percentage for sales out policies
    ) {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;
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
                "renewal_percentage" =>  $renewal_percentage,
                "sales_out_percentage" =>  $sales_out_percentage,
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
        $base_payment = null,
        $max_income_target = null,
        Carbon $next_run_date = null,
        $is_end_of_month = false,
        $renewal_percentage = null,
        $sales_out_percentage = null,
    ) {
        try {
            AppLog::info("Creating comm profile target", loggable: $this);
            $order = $this->targets()->count() + 1;
            $target = $this->targets()->create([
                "day_of_month"      =>  $day_of_month,
                "each_month"        =>  $each_month,
                "prem_target"       =>  $prem_target,
                "comm_percentage"   =>  $comm_percentage,
                "renewal_percentage" =>  $renewal_percentage,
                "sales_out_percentage" =>  $sales_out_percentage,
                "min_income_target" =>  $min_income_target,
                "base_payment"      =>  $base_payment,
                "max_income_target" =>  $max_income_target,
                "is_end_of_month"   =>  $is_end_of_month,
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
        $linked_sales_comms = [],
        ?Carbon $target_date = null
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
                "note"      =>  $note,
                "target_date" => $target_date?->format('Y-m-d') ?? null
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
    public function scopeByTitle($query, $text)
    {
        return $query->where('title', '=', "$text");
    }
    public function scopeByUserIds($query, array $user_ids)
    {
        return $query->whereIn('user_id', $user_ids);
    }
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
    public function scopeAvailableForSelection($query)
    {
        return $query->where(function ($q) {
            $q->where('select_available', 1)
                ->orWhere('available_for_id', Auth::id());
        });
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
        return $this->belongsToMany(SoldPolicy::class, 'sales_comms')
            ->withPivot('sales_comms.status')
            ->wherePivotNotIn('sales_comms.status', [SalesComm::PYMT_STATE_CANCELLED]);
    }

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, "offer_comm_profiles");
    }

    /**
     * Get the account associated with the commission profile.
     */
    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class);
    }
}
