<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
use App\models\Insurance\Policy;
use App\Models\Customers\Customer;
use App\Models\Corporates\Corporate;
use App\Models\Offers\OfferOption;
use App\Models\Users\User;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Traits\AlertFrontEnd;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;

class SoldPolicyIndex extends Component
{
    use WithPagination, AlertFrontEnd, WithFileUploads;

    public $search;

    public $clientStatus;
    public $clientType = 'Customer';
    public $searchClient;
    public $clientNames;
    public $selectedClientId;
    public $selectedClientName;

    public $policyStatus;
    public $searchPolicy;
    public $policyData;
    public $selectedPolicyId;
    public $selectedPolicyName;
    public $dateRange;
    public $startDate;
    public $endDate;

    public $client;
    public $policy_id;
    public $policy_number;
    public $insured_value;
    public $net_rate;
    public $net_premium;
    public $gross_premium;
    public $installments_count;
    public $payment_frequency;
    public $issuing_date;
    public $start;
    public $expiry;
    public $discount = 0;
    public $offer_id = null;
    public $customer_car_id = null;
    public $car_chassis = null;
    public $car_plate_no = null;
    public $car_engine = null;
    public $is_valid = true;
    public $note = null;
    public $inFavorTo = null;
    public $policyDoc = null;

    public $newPolicySection = false;
    public $isPaidCB = 'all';

    // Add review modal properties
    public $reviewModal = false;
    public $policyToReview = null;
    public $reviewStatus = false;
    public $reviewValidData = false;
    public $reviewComment = null;

    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function openNewPolicySection()
    {
        $this->newPolicySection = true;
    }

    public function closeNewPolicySection()
    {
        $this->newPolicySection = false;
        $this->reset();
    }

    public function addSoldPolicy()
    {
        $this->validate([
            'policy_id' => 'required|numeric|exists:policies,id',
            'policy_number' => 'required|string|max:255',
            'insured_value' => 'required|numeric',
            'net_rate' => 'required|numeric|between:0,100',
            'net_premium' => 'required|numeric',
            'gross_premium' => 'required|numeric',
            'installments_count' => 'required|numeric',
            'payment_frequency' => 'required|string|max:255',
            'start' => 'required|date|after:2020-01-01',
            'expiry' => 'required|date|after:start',
            'discount' => 'numeric',
            'offer_id' => 'nullable|numeric|exists:offers,id',
            'customer_car_id' => 'nullable|numeric|exists:customer_cars,id',
            'car_chassis' => 'nullable|string|max:255',
            'car_plate_no' => 'nullable|string|max:255',
            'car_engine' => 'nullable|string|max:255',
            'is_valid' => 'required|boolean',
            'note' => 'nullable|string|max:255',
            'inFavorTo' => 'nullable|string|max:255',
            'policyDoc' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
            'issuing_date' => 'required|date',
        ]);

        if ($this->policyDoc) {
            $url = $this->policyDoc->store(SoldPolicy::FILES_DIRECTORY, 's3');
        } else {
            $url = null;
        }


        $res = SoldPolicy::newSoldPolicy(
            $this->client,
            $this->policy_id,
            $this->policy_number,
            $this->insured_value,
            $this->net_rate,
            $this->net_premium,
            $this->gross_premium,
            $this->installments_count,
            $this->payment_frequency,
            Carbon::parse($this->start),
            Carbon::parse($this->expiry),
            $this->discount,
            $this->offer_id,
            $this->customer_car_id,
            $this->car_chassis,
            $this->car_plate_no,
            $this->car_engine,
            $this->is_valid,
            $this->note,
            $this->inFavorTo,
            $url,
            Carbon::parse($this->issuing_date)
        );

        if ($res) {
            $this->reset();
            $this->closeNewPolicySection();
            $this->alert('success', 'Sold Policy added');
        } else {
            $this->alert('failed', 'server error');
        }
    }
    public function selectPolicy($id)
    {
        $res = Policy::find($id);

        $this->policyStatus = $res;

        $this->selectedPolicyName = $res->company->name . ' · ' . $res->name;
        $this->policy_id  = $res->id;
        $this->policyData = null;
        $this->searchPolicy = null;
    }

    public function updatedSearchPolicy()
    {
        $this->policyData = Policy::searchBy(text: $this->searchPolicy)
            ->get()
            ->take(5);
        $tmp = $this->policyData;
    }

    public function selectClient($id)
    {
        if ($this->clientType == 'Customer') {
            $this->client = Customer::find($id);
        } elseif ($this->clientType == 'Corporate') {
            $this->client = Corporate::find($id);
        }

        $this->clientStatus = $this->client;
        if ($this->clientType == 'Customer') {
            $this->selectedClientName = $this->client->first_name . ' ' . $this->client->middle_name . ' ' . $this->client->last_name;
        } else {
            $this->selectedClientName = $this->client->name;
        }
        $this->clientNames = null;
        $this->searchClient = null;
    }

    public function updatedClientType()
    {
        $this->clientNames = null;
        $this->searchClient = null;
    }

    public function updatedSearchClient()
    {
        if ($this->clientType == 'Customer' && !$this->searchClient == '') {
            $this->clientNames = Customer::userData($this->searchClient, false)
                ->get()
                ->take(5);
        } elseif ($this->clientType == 'Corporate' && !$this->searchClient == '') {
            $this->clientNames = Corporate::userData($this->searchClient, false)
                ->get()
                ->take(5);
        }
    }

    //reseting page while searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedDateRange()
    {
        if (strpos($this->dateRange, 'to') !== false) {
            // The string contains 'to'
            [$this->startDate, $this->endDate] = explode(' to ', $this->dateRange);
            // dd($this->startDate, $this->endDate);
        }
    }

    public function mount()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->dateRange = ($this->startDate && $this->endDate) ? $this->startDate . ' to ' . $this->endDate : "N/A";
    }

    public function render()
    {
        $soldPolicies = SoldPolicy::userData(searchText: $this->search)
            ->with('offer', 'creator')
            ->when($this->isPaidCB, function ($q, $v) {
                if ($v === 'isPaid') return $q->byPaid(1);
                elseif ($v === 'notPaid') return $q->byPaid(0);
            })->when($this->startDate && $this->endDate, function ($query) {
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                return $query->fromTo($startDate, $endDate);
            })
            ->paginate(20);
            
        $PAYMENT_FREQS = OfferOption::PAYMENT_FREQS;

        return view(
            'livewire.sold-policy-index',
            [
                'soldPolicies' => $soldPolicies,
                'PAYMENT_FREQS' => $PAYMENT_FREQS,
            ]
        );
    }

    // Add review modal methods
    public function openReviewModal($policyId)
    {
        $this->policyToReview = SoldPolicy::find($policyId);
        if ($this->policyToReview) {
            $this->reviewModal = true;
            $this->reviewStatus = $this->policyToReview->is_reviewed ?? false;
            $this->reviewValidData = $this->policyToReview->is_valid_data ?? false;
            $this->reviewComment = $this->policyToReview->review_comment;
        }
    }
    
    public function closeReviewModal()
    {
        $this->reviewModal = false;
        $this->policyToReview = null;
        $this->reviewStatus = false;
        $this->reviewValidData = false;
        $this->reviewComment = null;
    }
    
    public function saveReview()
    {
        if (!$this->policyToReview) {
            $this->alert('failed', 'Policy not found');
            return;
        }
        
        $this->validate([
            'reviewComment' => 'nullable|string|max:500',
        ]);
        
        $res = $this->policyToReview->setIsReviewed(
            $this->reviewStatus, 
            $this->reviewValidData, 
            $this->reviewComment
        );
        
        if ($res !== false) {
            $this->closeReviewModal();
            $this->alert('success', 'Review status updated successfully!');
        } else {
            $this->alert('failed', 'You do not have permission to review this policy.');
        }
    }
}
