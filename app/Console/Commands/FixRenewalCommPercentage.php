<?php

namespace App\Console\Commands;

use App\Models\Payments\SalesComm;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FixRenewalCommPercentage extends Command
{
    /** Renewal/sales out percentages became multipliers of the base percentage in commit 6e75730c
     * but were multiplied raw (30 * 66.68 = 2000.4% instead of 30 * 0.6668 = 20.004%).
     * Anything calculated before that commit used the old "replacement percentage" semantics and must not be touched. */
    const BUG_INTRODUCED_AT = '2025-10-27 14:17:48';

    protected $signature = 'sales-comms:fix-renewal-percentage
                            {--since= : Only check comms created/updated after this date (defaults to the buggy release date)}
                            {--profile= : Limit to one comm profile id}
                            {--include-paid : Also recalculate comms that are already paid}
                            {--chunk=200 : How many comms to load at a time}
                            {--dry-run : Report what would change without saving}';

    protected $description = 'Fixes sales comms whose percentage was multiplied by a raw renewal/sales out percentage (100x too high)';

    public function handle()
    {
        $since = $this->option('since') ? Carbon::parse($this->option('since')) : Carbon::parse(self::BUG_INTRODUCED_AT);
        $dryRun = (bool) $this->option('dry-run');
        $includePaid = (bool) $this->option('include-paid');
        $chunk = max(1, (int) $this->option('chunk'));

        $this->info(($dryRun ? '[DRY RUN] ' : '') . 'Checking sales comms created/updated after ' . $since->format('Y-m-d H:i:s'));

        $query = SalesComm::query()
            ->where('comm_percentage', '>', 0)
            ->where('status', '!=', SalesComm::PYMT_STATE_CANCELLED)
            ->where(function ($q) use ($since) {
                $q->where('created_at', '>=', $since)->orWhere('updated_at', '>=', $since);
            })
            ->with(['sold_policy.policy', 'sold_policy.offer', 'comm_profile.configurations']);

        if ($this->option('profile'))
            $query->where('comm_profile_id', (int) $this->option('profile'));

        $checked = 0;
        $fixed = 0;
        $amountDelta = 0;
        $rows = [];
        $paidSkipped = [];
        $profiles = [];

        $query->chunkById($chunk, function ($comms) use (&$checked, &$fixed, &$amountDelta, &$rows, &$paidSkipped, &$profiles, $dryRun, $includePaid) {
            /** @var SalesComm $comm */
            foreach ($comms as $comm) {
                $checked++;

                //is_direct is a string column, so mirror refreshPaymentInfo's own truthiness check.
                //the bug only ever ran in the configuration branch, direct comms never went through it
                if ($comm->is_direct) continue;
                if (!$comm->sold_policy || !$comm->comm_profile) continue;
                $conf = $comm->comm_profile->getValidDirectCommissionConf($comm->sold_policy->policy);
                if (!$conf) continue;

                $isRenewal = (bool) ($comm->sold_policy->offer?->is_renewal ?? false);
                $hasSalesOut = (bool) $comm->sold_policy->has_sales_out;

                //what the buggy code produced vs what it should have produced, for this exact policy/config
                $buggy = $correct = (float) $conf->percentage;
                if ($isRenewal && $conf->renewal_percentage > 0) {
                    $buggy *= $conf->renewal_percentage;
                    $correct *= $conf->renewal_percentage / 100;
                }
                if ($hasSalesOut && $conf->sales_out_percentage > 0) {
                    $buggy *= $conf->sales_out_percentage;
                    $correct *= $conf->sales_out_percentage / 100;
                }

                //no modifier applies -> the bug could never have touched this comm
                if (abs($buggy - $correct) < 0.000001) continue;

                //only touch comms whose stored percentage is exactly what the buggy formula produces,
                //so manually set, target driven and pre-fix percentages are all left alone
                if (abs($comm->comm_percentage - $buggy) > 0.0001) continue;

                if ($comm->status === SalesComm::PYMT_STATE_PAID && !$includePaid) {
                    $paidSkipped[] = [$comm->id, $comm->comm_profile->title, $comm->sold_policy_id, round($comm->comm_percentage, 4), round($correct, 4), round($comm->amount, 2)];
                    continue;
                }

                $oldPercentage = (float) $comm->comm_percentage;
                $oldAmount = (float) $comm->amount;

                if (!$dryRun) {
                    //recalculates the percentage from the (now fixed) configuration and rewrites the amount
                    $comm->refreshPaymentInfo(false, false, true, true);
                    $comm->refresh();
                    $profiles[$comm->comm_profile_id] = $comm->comm_profile;
                    Log::info("Fixed sales comm #$comm->id percentage", [
                        'from_percentage' => $oldPercentage,
                        'to_percentage'   => $comm->comm_percentage,
                        'from_amount'     => $oldAmount,
                        'to_amount'       => $comm->amount,
                    ]);
                }

                $newAmount = $dryRun ? null : (float) $comm->amount;
                $amountDelta += ($newAmount ?? 0) - $oldAmount;
                $fixed++;
                $rows[] = [
                    $comm->id,
                    $comm->comm_profile->title,
                    $comm->sold_policy_id,
                    $comm->status,
                    round($oldPercentage, 4) . ' -> ' . round($correct, 4),
                    number_format($oldAmount, 2) . ' -> ' . ($newAmount === null ? '?' : number_format($newAmount, 2)),
                ];
            }
        });

        if ($rows)
            $this->table(['Comm', 'Profile', 'Policy', 'Status', 'Percentage %', 'Amount'], $rows);

        if ($paidSkipped) {
            $this->warn(count($paidSkipped) . ' paid comm(s) are also affected and were skipped, re-run with --include-paid to fix them:');
            $this->table(['Comm', 'Profile', 'Policy', 'Current %', 'Correct %', 'Amount'], $paidSkipped);
        }

        foreach ($profiles as $profile) {
            $profile->refreshBalances();
            $this->line("Refreshed balances for profile #{$profile->id} ({$profile->title})");
        }

        $this->info("Checked $checked comm(s), " . ($dryRun ? "$fixed would be fixed" : "fixed $fixed") . '.');
        if (!$dryRun && $fixed)
            $this->info('Total amount change: ' . number_format($amountDelta, 2) . ' EGP across ' . count($profiles) . ' profile(s).');

        return Command::SUCCESS;
    }
}
