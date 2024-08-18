<?php

namespace App\Http\Livewire;

use App\Models\Business\SoldPolicy;
use App\Models\Insurance\Company;
use App\Models\Payments\Invoice;
use App\Traits\AlertFrontEnd;
use Livewire\Component;

class CompanyShow extends Component
{
    use AlertFrontEnd;
    protected $queryString = ['section'];

    public $company;
    public $section = 'info';

    public $serial;
    public $gross_total;
    public $tax_total;
    public $net_total;

    public $newInvoiceSection = false;

    public $sold_policies_entries = [];
    public $available_policies;
    public $selected_policy_id = null;
    public $amount = '';
    public $pymnt_perm = '';

    public function openNewInvoiceSec(){
        $this->newInvoiceSection = true;
    }

    public function closeNewInvoiceSec(){
        $this->newInvoiceSection = false;
    }

    protected $rules = [
        'serial' => ['required', 'integer', 'unique:invoices'],
        'gross_total' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        'tax_total' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        'net_total' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        'sold_policies_entries.*.id' => 'required|integer|exists:policies,id', // Ensure 'policies' is your table and 'id' is a valid ID
        'sold_policies_entries.*.amount' => 'required|numeric|min:0',
        'sold_policies_entries.*.pymnt_perm' => 'required|numeric|min:0',
    ];

    public function updatedGrossTotal()
    {
        if ($this->gross_total >= 0) {
            $this->tax_total = $this->gross_total * .05;
            $this->net_total = $this->gross_total * .95;
        } else {
            $this->tax_total = null;
            $this->net_total = null;
        }
    }

    public function addInvoice()
    {
        $this->validate([
            'serial' => 'required|string',
            'gross_total' => 'required|numeric',
            'tax_total' => 'required|numeric',
            'net_total' => 'required|numeric',
            'sold_policies_entries.*.id' => 'required|integer',
            'sold_policies_entries.*.amount' => 'required|numeric',
            'sold_policies_entries.*.pymnt_perm' => 'required|string',
            
        ],attributes:[
            'sold_policies_entries.*.amount' => 'amount',
            'sold_policies_entries.*.pymnt_perm' => 'payment perm',
        ]);

        $res = Invoice::newInvoice(
            $this->company->id,
            $this->serial,
            $this->gross_total,
            $this->sold_policies_entries
        );

        if ($res) {
            $this->reset(['serial','gross_total','tax_total','sold_policies_entries.*.id','sold_policies_entries.*.amount','sold_policies_entries.*.pymnt_perm']);
            $this->closeNewInvoiceSec();
            $this->alert('success' , 'invoice added');
        }else{
            $this->alert('failed','server error');
        }
        
     }

    public function selectPolicy($policyId)
    {
        $policy = $this->available_policies->firstWhere('id', $policyId);

        if ($policy && !in_array($policyId, array_column($this->sold_policies_entries, 'id'))) {
            $this->sold_policies_entries[] = [
                'id' => $policyId,
                'amount' => '',
                'pymnt_perm' => '',
            ];
        }
    }

    public function addEntry()
    {
        $this->sold_policies_entries[] = [
            'id' => '',
            'amount' => '',
            'pymnt_perm' => '',
        ];
    }

    public function removeEntry($index)
    {
        unset($this->sold_policies_entries[$index]);
        $this->sold_policies_entries = array_values($this->sold_policies_entries); // Re-index array
    }

    public function changeSection($section)
    {
        $this->section = $section;
        $this->mount($this->company->id);
    }

    public function mount($company_id)
    {
        $this->company = Company::find($company_id);
        $this->available_policies = SoldPolicy::userData()->ByCompany(company_id: $company_id)->get();
        // dd($this->available_policies);
    }

    public function render()
    {
        return view('livewire.company-show');
    }
}
