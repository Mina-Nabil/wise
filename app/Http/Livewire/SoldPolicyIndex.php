<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
use App\models\Insurance\Policy;
use App\Models\Customers\Customer;
use App\Models\Corporates\Corporate;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Traits\AlertFrontEnd;

class SoldPolicyIndex extends Component
{
    use WithPagination,AlertFrontEnd;

    public $search;

    public $clientStatus;
    public $clientType = 'Customer';
    public $searchClient ;
    public $clientNames;
    public $selectedClientId;
    public $selectedClientName;

    public $policyStatus;
    public $searchPolicy;
    public $policyData;
    public $selectedPolicyId;
    public $selectedPolicyName;

    public $client;
    public $policy_id;
    public $policy_number;
    public $insured_value;
    public $net_rate;
    public $net_premium;
    public $gross_premium;
    public $installments_count;
    public $payment_frequency;
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

    public $newPolicySection = false;

    public function openNewPolicySection(){
        $this->newPolicySection = true;
    }

    public function closeNewPolicySection(){
        $this->newPolicySection = false;
        $this->reset();
    }

    public function addSoldPolicy(){
        $this->validate([
            'policy_id' => 'required|numeric|exists:policies,id',
            'policy_number' => 'required|string|max:255',
            'insured_value' => 'required|numeric',
            'net_rate' => 'required|numeric',
            'net_premium' => 'required|numeric',
            'gross_premium' => 'required|numeric',
            'installments_count' => 'required|numeric',
            'payment_frequency' => 'required|string|max:255',
            'start' => 'required|date',
            'expiry' => 'required|date|after:start',
            'discount' => 'numeric',
            'offer_id' => 'nullable|numeric|exists:offers,id',
            'customer_car_id' => 'nullable|numeric|exists:customer_cars,id',
            'car_chassis' => 'nullable|string|max:255',
            'car_plate_no' => 'nullable|string|max:255',
            'car_engine' => 'nullable|string|max:255',
            'is_valid' => 'required|boolean',
            'note' => 'nullable|string|max:255',
        ]);

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
            $this->note
        );

        if($res){
            $this->reset();
            $this->closeNewPolicySection();
            $this->alert('success', 'Sold Policy added');
        }else{
            $this->alert('failed', 'server error');
        }
        
    }
    public function selectPolicy($id)
    {
            $res = Policy::find($id);

        $this->policyStatus = $res;

        $this->selectedPolicyName = $res->company->name.' · '.$res->name;
        $this->policy_id  = $res->id;
        $this->policyData = null;
        $this->searchPolicy = null;
    }

    public function updatedSearchPolicy()
    {
            $this->policyData = Policy::searchBy(text: $this->searchPolicy)
                ->get()
                ->take(5);
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
        }else{
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
            $this->clientNames = Customer::userData(searchText: $this->searchClient)
                ->get()
                ->take(5);
        } elseif ($this->clientType == 'Corporate' && !$this->searchClient == '') {
            $this->clientNames = Corporate::where('name', 'like', '%' . $this->searchClient . '%')
                ->get()
                ->take(5);
        }
    }

    public function render()
    {
        $soldPolicies = SoldPolicy::userData(searchText:$this->search)->paginate(20);
        return view('livewire.sold-policy-index',[
            'soldPolicies' => $soldPolicies,
            ]
        );
    }
}
