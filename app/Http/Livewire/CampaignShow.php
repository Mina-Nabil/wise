<?php

namespace App\Http\Livewire;

use App\Models\Marketing\Campaign;
use App\Models\Customers\Customer;
use App\Models\Corporates\Corporate;
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
    }

    public function render()
    {
        $customers = null;
        $corporates = null;

        if ($this->section === 'customers') {
            $customers = Customer::where('campaign_id', $this->campaignId)
                ->when($this->customerSearch, function ($q) {
                    $q->where(function ($inner) {
                        $inner->where('first_name', 'like', '%' . $this->customerSearch . '%')
                            ->orWhere('last_name', 'like', '%' . $this->customerSearch . '%');
                    });
                })
                ->withCount(['soldpolicies', 'offers'])
                ->with(['phones' => fn($q) => $q->limit(1)])
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

        return view('livewire.campaign-show', [
            'customers' => $customers,
            'corporates' => $corporates,
        ])->layout('layouts.app', ['page_title' => $this->campaign->name]);
    }
}
