<?php

namespace App\Http\Livewire;

use App\Models\Marketing\Campaign;
use App\Models\Customers\Customer;
use App\Models\Corporates\Corporate;
use App\Models\Business\SoldPolicy;
use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignShow extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $paginationTheme = 'bootstrap';

    public $campaignId;
    public $campaign;
    public $section = 'overview';

    public $customerSearch = '';
    public $corporateSearch = '';
    public $soldPoliciesSearch = '';
    public $offersSearch = '';

    // Stats
    public $totalCustomers;
    public $totalCorporates;
    public $totalClients;
    public $customersWithPolicies;
    public $corporatesWithPolicies;
    public $totalWithPolicies;
    public $customersWithOffers;
    public $corporatesWithOffers;
    public $totalWithOffers;
    public $totalSoldPoliciesCount;
    public $totalOffersCount;

    // ROI
    public $grossIncome;
    public $netIncome;
    public $roi;

    public function mount($id)
    {
        $this->campaignId = $id;
        $this->campaign = Campaign::findOrFail($id);
        $this->authorize('viewAny', Campaign::class);
        $this->loadStats();
    }

    public function changeSection($section)
    {
        $this->section = $section;
        $this->resetPage();
    }

    public function updatedCustomerSearch()
    {
        $this->resetPage('customersPage');
    }

    public function updatedCorporateSearch()
    {
        $this->resetPage('corporatesPage');
    }

    public function updatedSoldPoliciesSearch()
    {
        $this->resetPage('soldPoliciesPage');
    }

    public function updatedOffersSearch()
    {
        $this->resetPage('offersPage');
    }

    private function loadStats()
    {
        $campaignId = $this->campaignId;

        $this->totalCustomers = Customer::where('campaign_id', $campaignId)->count();
        $this->totalCorporates = Corporate::where('campaign_id', $campaignId)->count();
        $this->totalClients = $this->totalCustomers + $this->totalCorporates;

        $this->customersWithPolicies = Customer::where('campaign_id', $campaignId)
            ->has('soldpolicies')
            ->count();
        $this->corporatesWithPolicies = Corporate::where('campaign_id', $campaignId)
            ->has('soldpolicies')
            ->count();
        $this->totalWithPolicies = $this->customersWithPolicies + $this->corporatesWithPolicies;

        $this->customersWithOffers = Customer::where('campaign_id', $campaignId)
            ->has('offers')
            ->count();
        $this->corporatesWithOffers = Corporate::where('campaign_id', $campaignId)
            ->has('offers')
            ->count();
        $this->totalWithOffers = $this->customersWithOffers + $this->corporatesWithOffers;

        $customerIds = Customer::where('campaign_id', $campaignId)->pluck('id');
        $corporateIds = Corporate::where('campaign_id', $campaignId)->pluck('id');

        $this->totalSoldPoliciesCount = SoldPolicy::where(function ($q) use ($customerIds, $corporateIds) {
            $q->where(function ($i) use ($customerIds) {
                $i->where('client_type', Customer::MORPH_TYPE)->whereIn('client_id', $customerIds);
            })->orWhere(function ($i) use ($corporateIds) {
                $i->where('client_type', Corporate::MORPH_TYPE)->whereIn('client_id', $corporateIds);
            });
        })->count();

        $this->totalOffersCount = Offer::where(function ($q) use ($customerIds, $corporateIds) {
            $q->where(function ($i) use ($customerIds) {
                $i->where('client_type', Customer::MORPH_TYPE)->whereIn('client_id', $customerIds);
            })->orWhere(function ($i) use ($corporateIds) {
                $i->where('client_type', Corporate::MORPH_TYPE)->whereIn('client_id', $corporateIds);
            });
        })->count();

        // ROI calculation
        $totals = SoldPolicy::where(function ($q) use ($customerIds, $corporateIds) {
            $q->where(function ($i) use ($customerIds) {
                $i->where('client_type', Customer::MORPH_TYPE)->whereIn('client_id', $customerIds);
            })->orWhere(function ($i) use ($corporateIds) {
                $i->where('client_type', Corporate::MORPH_TYPE)->whereIn('client_id', $corporateIds);
            });
        })->selectRaw('SUM(after_tax_comm) as gross_income, SUM(total_sales_comm) as total_sales_comm')->first();

        $this->grossIncome = (float) ($totals->gross_income ?? 0);
        $netIncome         = $this->grossIncome - (float) ($totals->total_sales_comm ?? 0);
        $this->netIncome   = $netIncome;

        $budget = (float) ($this->campaign->budget ?? 0);
        $this->roi = ($budget > 0)
            ? round((($netIncome - $budget) / $budget) * 100, 1)
            : null;
    }

    public function render()
    {
        $customers = null;
        $corporates = null;
        $soldPolicies = null;
        $offers = null;

        $customerIds = Customer::where('campaign_id', $this->campaignId)->pluck('id');
        $corporateIds = Corporate::where('campaign_id', $this->campaignId)->pluck('id');

        if ($this->section === 'customers') {
            $customers = Customer::where('campaign_id', $this->campaignId)
                ->when($this->customerSearch, function ($q) {
                    $q->where(function ($inner) {
                        $inner->where('first_name', 'like', '%' . $this->customerSearch . '%')
                            ->orWhere('last_name', 'like', '%' . $this->customerSearch . '%');
                    });
                })
                ->withCount(['soldpolicies', 'offers'])
                ->with(['phones' => fn($q) => $q->limit(1), 'interests'])
                ->latest()
                ->paginate(20, ['*'], 'customersPage');
        }

        if ($this->section === 'corporates') {
            $corporates = Corporate::where('campaign_id', $this->campaignId)
                ->when($this->corporateSearch, function ($q) {
                    $q->where('name', 'like', '%' . $this->corporateSearch . '%');
                })
                ->withCount(['soldpolicies', 'offers'])
                ->with(['phones' => fn($q) => $q->limit(1)])
                ->latest()
                ->paginate(20, ['*'], 'corporatesPage');
        }

        if ($this->section === 'sold_policies') {
            $soldPolicies = SoldPolicy::where(function ($q) use ($customerIds, $corporateIds) {
                    $q->where(function ($inner) use ($customerIds) {
                        $inner->where('client_type', Customer::MORPH_TYPE)
                              ->whereIn('client_id', $customerIds);
                    })->orWhere(function ($inner) use ($corporateIds) {
                        $inner->where('client_type', Corporate::MORPH_TYPE)
                              ->whereIn('client_id', $corporateIds);
                    });
                })
                ->when($this->soldPoliciesSearch, function ($q) {
                    $q->where('policy_number', 'like', '%' . $this->soldPoliciesSearch . '%');
                })
                ->with(['policy.company', 'client'])
                ->latest()
                ->paginate(20, ['*'], 'soldPoliciesPage');
        }

        if ($this->section === 'offers') {
            $offers = Offer::where(function ($q) use ($customerIds, $corporateIds) {
                    $q->where(function ($inner) use ($customerIds) {
                        $inner->where('client_type', Customer::MORPH_TYPE)
                              ->whereIn('client_id', $customerIds);
                    })->orWhere(function ($inner) use ($corporateIds) {
                        $inner->where('client_type', Corporate::MORPH_TYPE)
                              ->whereIn('client_id', $corporateIds);
                    });
                })
                ->when($this->offersSearch, function ($q) {
                    $q->where('item_title', 'like', '%' . $this->offersSearch . '%');
                })
                ->with(['client', 'creator'])
                ->latest()
                ->paginate(20, ['*'], 'offersPage');
        }

        return view('livewire.campaign-show', [
            'customers'       => $customers,
            'corporates'      => $corporates,
            'soldPolicies'    => $soldPolicies,
            'offers'          => $offers,
            'linesOfBusiness' => Policy::PERSONAL_TYPES,
        ])->layout('layouts.app', ['page_title' => $this->campaign->name]);
    }
}
