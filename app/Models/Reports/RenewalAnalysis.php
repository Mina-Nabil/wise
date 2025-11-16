<?php

declare(strict_types=1);

namespace App\Models\Reports;

use App\Models\Business\SoldPolicy;
use App\Models\Offers\Offer;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RenewalAnalysis
{
    /**
     * Calculate renewal analysis stats.
     *
     * Rules:
     * - Expiring sold policies: expiry within selected month range; only valid policies.
     * - Offers: linked to expiring policies via offers.renewal_policy_id; filter by creator_id if provided; regardless of offer creation date; exclude soft-deleted.
     * - New sold policies: linked via sold_policies.offer_id IN (offers ids); only valid policies; regardless of creation date.
     *
     * @return array{
     *   totalExpiringSoldPolicies:int,
     *   totalOffersForExpiring:int,
     *   totalNewSoldPoliciesFromOffers:int
     * }
     */
    public static function calculate(int $year, int $month, ?int $userId = null): array
    {
        $start = Carbon::create($year, $month, 1, 0, 0, 0)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $expiringPoliciesQuery = SoldPolicy::query()
            ->where('is_valid', true)
            ->whereBetween('expiry', [$start, $end]);

        /** @var Collection<int,int> $expiringPolicyIds */
        $expiringPolicyIds = $expiringPoliciesQuery->pluck('id');
        $sumNetExpiring = SoldPolicy::query()
            ->whereIn('id', $expiringPolicyIds->all())
            ->sum('net_premium');

        $totalExpiringSoldPolicies = $expiringPolicyIds->count();

        if ($totalExpiringSoldPolicies === 0) {
            return [
                'totalExpiringSoldPolicies' => 0,
                'totalOffersForExpiring' => 0,
                'totalNewSoldPoliciesFromOffers' => 0,
                'pctOffersOfExpiring' => 0.0,
                'pctNewOfOffers' => 0.0,
                'pctNewOfExpiring' => 0.0,
                'sumNetExpiring' => 0.0,
                'sumNetNewFromOffers' => 0.0,
                'pctNetNewOfExpiring' => 0.0,
            ];
        }

        $offersQuery = Offer::query()
            ->whereIn('renewal_policy_id', $expiringPolicyIds->all())
            ->whereNull('deleted_at'); // exclude soft-deleted offers

        if ($userId !== null) {
            $offersQuery->where('creator_id', $userId);
        }

        /** @var Collection<int,int> $offerIds */
        $offerIds = $offersQuery->pluck('id');
        $totalOffersForExpiring = $offerIds->count();

        $newSoldPoliciesFromOffers = 0;
        $sumNetNewFromOffers = 0.0;
        if ($totalOffersForExpiring > 0) {
            $newSoldPoliciesFromOffers = SoldPolicy::query()
                ->where('is_valid', true)
                ->whereIn('offer_id', $offerIds->all())
                ->count();
            $sumNetNewFromOffers = SoldPolicy::query()
                ->where('is_valid', true)
                ->whereIn('offer_id', $offerIds->all())
                ->sum('net_premium');
        }

        $pctOffersOfExpiring = self::pct($totalOffersForExpiring, $totalExpiringSoldPolicies);
        $pctNewOfOffers = self::pct($newSoldPoliciesFromOffers, $totalOffersForExpiring);
        $pctNewOfExpiring = self::pct($newSoldPoliciesFromOffers, $totalExpiringSoldPolicies);
        $pctNetNewOfExpiring = self::pctFloat($sumNetNewFromOffers, $sumNetExpiring);

        return [
            'totalExpiringSoldPolicies' => $totalExpiringSoldPolicies,
            'totalOffersForExpiring' => $totalOffersForExpiring,
            'totalNewSoldPoliciesFromOffers' => $newSoldPoliciesFromOffers,
            'pctOffersOfExpiring' => $pctOffersOfExpiring,
            'pctNewOfOffers' => $pctNewOfOffers,
            'pctNewOfExpiring' => $pctNewOfExpiring,
            'sumNetExpiring' => (float) $sumNetExpiring,
            'sumNetNewFromOffers' => (float) $sumNetNewFromOffers,
            'pctNetNewOfExpiring' => $pctNetNewOfExpiring,
        ];
    }

    /**
     * Yearly breakdown per month (1..12) with counts and percentages.
     *
     * @return array<int,array{
     *   month:int,
     *   name:string,
     *   totalExpiringSoldPolicies:int,
     *   totalOffersForExpiring:int,
     *   totalNewSoldPoliciesFromOffers:int,
     *   pctOffersOfExpiring:float,
     *   pctNewOfOffers:float,
     *   pctNewOfExpiring:float
     * }>
     */
    public static function calculateYearly(int $year, ?int $userId = null): array
    {
        $rows = [];
        for ($m = 1; $m <= 12; $m++) {
            $stats = self::calculate($year, $m, $userId);
            $rows[] = [
                'month' => $m,
                'name' => Carbon::create($year, $m, 1)->format('F'),
                'totalExpiringSoldPolicies' => $stats['totalExpiringSoldPolicies'],
                'totalOffersForExpiring' => $stats['totalOffersForExpiring'],
                'totalNewSoldPoliciesFromOffers' => $stats['totalNewSoldPoliciesFromOffers'],
                'pctOffersOfExpiring' => $stats['pctOffersOfExpiring'],
                'pctNewOfOffers' => $stats['pctNewOfOffers'],
                'pctNewOfExpiring' => $stats['pctNewOfExpiring'],
                'sumNetExpiring' => $stats['sumNetExpiring'],
                'sumNetNewFromOffers' => $stats['sumNetNewFromOffers'],
                'pctNetNewOfExpiring' => $stats['pctNetNewOfExpiring'],
            ];
        }
        return $rows;
    }

    private static function pct(int $num, int $den): float
    {
        if ($den === 0) {
            return 0.0;
        }
        return round(($num / $den) * 100, 2);
    }

    private static function pctFloat(float $num, float $den): float
    {
        if ($den == 0.0) {
            return 0.0;
        }
        return round(($num / $den) * 100, 2);
    }
}


