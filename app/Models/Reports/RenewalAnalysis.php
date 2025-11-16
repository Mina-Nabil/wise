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

        $totalExpiringSoldPolicies = $expiringPolicyIds->count();

        if ($totalExpiringSoldPolicies === 0) {
            return [
                'totalExpiringSoldPolicies' => 0,
                'totalOffersForExpiring' => 0,
                'totalNewSoldPoliciesFromOffers' => 0,
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
        if ($totalOffersForExpiring > 0) {
            $newSoldPoliciesFromOffers = SoldPolicy::query()
                ->where('is_valid', true)
                ->whereIn('offer_id', $offerIds->all())
                ->count();
        }

        return [
            'totalExpiringSoldPolicies' => $totalExpiringSoldPolicies,
            'totalOffersForExpiring' => $totalOffersForExpiring,
            'totalNewSoldPoliciesFromOffers' => $newSoldPoliciesFromOffers,
        ];
    }
}


