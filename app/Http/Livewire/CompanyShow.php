<?php

namespace App\Http\Livewire;

use App\Models\Business\SoldPolicy;
use App\Models\Insurance\Company;
use App\Models\Insurance\CompanyEmail;
use App\Models\Payments\Invoice;
use App\Traits\AlertFrontEnd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyShow extends Component
{
    use AlertFrontEnd, WithPagination;
    protected $queryString = ['section'];

    public $company;
    public $section = 'invoices';

    public $serial;
    public $gross_total;
    public $tax_total;
    public $net_total;

    public $seachAllSoldPolicies; // for sold policy tab
    public $seachAvailablePoliciesText;
    public $availableSoldPolicies_isNotPaid = "0";

    public $newInvoiceSection = false;

    public $sold_policies_entries = [];
    protected $available_policies;
    public $selected_policy_id = null;
    public $amount = '';
    public $pymnt_perm = '';

    public $companyInfoName;
    public $companyInfoNote;
    public $editInfoSec;

    protected $listeners = ['deleteInvoice', 'confirmInvoice']; //functions need confirmation

    public $newEmailSec = false;
    public $type = CompanyEmail::TYPES[0];
    public $email;
    public $is_primary = false;
    public $first_name;
    public $last_name;
    public $note;

    public $confirmInvoiceId;
    public $confirmDate;

    public $Emailtypes = CompanyEmail::TYPES;


    public function openConfirmInvoice(){
        $this->confirmInvoiceId = true;
    }

    public function closeConfirmInvoice(){
        $this->confirmInvoiceId = false;
        $this->confirmDate = null;
    }

    public function addEmail()
    {
        $this->validate([
            'type' => 'required|in:' . implode(',', CompanyEmail::TYPES),
            'email' => 'required|email',
            'is_primary' => 'boolean',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $res = $this->company->addEmail($this->type, $this->email, $this->is_primary, $this->first_name, $this->last_name, $this->note);

        if ($res) {
            $this->mount($this->company->id, false);
            $this->alert('success', 'email added');
            $this->reset(['newEmailSec']);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function confirmInvoice()
    {
        $res = Invoice::find($this->confirmInvoiceId)->confirmInvoice(Carbon::parse($this->confirmDate));

        if ($res) {
            $this->mount($this->company->id, false);
            $this->closeConfirmInvoice();
            $this->alert('success', 'invoice confirmed');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function openNewEmail()
    {
        $this->newEmailSec = true;
    }

    public function closeNewEmail()
    {
        $this->newEmailSec = false;
    }

    public function openEditInfo()
    {
        $this->companyInfoName = $this->company->name;
        $this->companyInfoNote = $this->company->note;
        $this->editInfoSec = true;
    }

    public function closeEditInfo()
    {
        $this->reset(['companyInfoName', 'companyInfoNote', 'editInfoSec']);
    }

    public function saveChanges()
    {
        $this->validate(
            [
                'companyInfoName' => 'required|string|max:255',
                'companyInfoNote' => 'string',
            ],
            [],
            [
                'companyInfoName' => 'Company Name',
                'companyInfoNote' => 'Note',
            ],
        );

        $company = Company::findOrFail($this->company->id);
        $success = $company->editInfo($this->companyInfoName, $this->companyInfoNote);

        if ($success) {
            $this->closeEditInfo();
            $this->alert('success', 'Company updated!');
            $this->mount($this->company->id, false);
        } else {
            $this->alert('failed', 'Server error!');
        }
    }

    public function printInvoice($id)
    {
        return Invoice::find($id)->printInvoice();
    }

    public function deleteInvoice($id)
    {
        $res = Invoice::find($id)->deleteInvoice();
        if ($res) {
            $this->alert('success', 'invoice deleted');
            $this->mount($this->company->id, false);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function openNewInvoiceSec()
    {
        $this->newInvoiceSection = true;
    }

    public function closeNewInvoiceSec()
    {
        $this->newInvoiceSection = false;
    }

    protected $rules = [
        'serial' => ['required', 'integer', 'unique:invoices'],
        'gross_total' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        'tax_total' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        'net_total' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        'sold_policies_entries.*.id' => 'required|integer|exists:policies,id', // Ensure 'policies' is your table and 'id' is a valid ID
        'sold_policies_entries.*.amount' => 'required|numeric|min:0',
        'sold_policies_entries.*.pymnt_perm' => 'numeric|min:0',
    ];

    public function updatedGrossTotal()
    {
        if ($this->gross_total >= 0) {
            $this->tax_total = round($this->gross_total * 0.05, 2);
            $this->net_total = round($this->gross_total * 0.95, 2);
        } else {
            $this->tax_total = null;
            $this->net_total = null;
        }
    }

    public function addInvoice()
    {
        $this->validate(
            [
                'serial' => 'required',
                'gross_total' => 'required|numeric',
                'tax_total' => 'required|numeric',
                'net_total' => 'required|numeric',
                'sold_policies_entries.*.id' => 'required|integer',
                'sold_policies_entries.*.amount' => 'required|numeric',
                'sold_policies_entries.*.pymnt_perm' => 'string',
            ],
            attributes: [
                'sold_policies_entries.*.amount' => 'amount',
                'sold_policies_entries.*.pymnt_perm' => 'payment perm',
            ],
        );

        $res = Invoice::newInvoice($this->company->id, $this->serial, $this->gross_total, $this->sold_policies_entries);

        if ($res) {
            $this->reset(['serial', 'gross_total', 'tax_total', 'sold_policies_entries']);
            $this->closeNewInvoiceSec();
            $this->alert('success', 'invoice added');
            $this->mount($this->company->id, false);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function selectPolicy($policyId)
    {
        $policy = SoldPolicy::firstWhere('id', $policyId);

        if ($policy && !in_array($policyId, array_column($this->sold_policies_entries, 'id'))) {
            $this->sold_policies_entries[] = [
                'id' => $policyId,
                'amount' => $policy->commission_left,
                'pymnt_perm' => '',
            ];
        }
        $this->updateTotal();
    }

    public function unselectPolicy($policyId)
    {
        foreach ($this->sold_policies_entries as $i => $e) {
            if ($e['id'] == $policyId) {
                unset($this->sold_policies_entries[$i]);
            }
        }
        $this->updateTotal();
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
        $this->mount($this->company->id, false);
    }

    public function updateTotal()
    {
        $this->gross_total = 0;
        foreach ($this->sold_policies_entries as $e) {
            $this->gross_total += is_numeric($e['amount']) ? $e['amount'] : 0;
        }
        $this->updatedGrossTotal();
    }

    public function mount($company_id, $updateSerial = true)
    {
        if ($updateSerial) {
            $this->serial = Invoice::getNextSerial();
        }
        $this->company = Company::find($company_id);
    }

    public function updatedSeachAvailablePoliciesText()
    {
        $this->resetPage();
    }

    public function updatedAvailableSoldPoliciesIsPaid()
    {
        // dd($this->availableSoldPolicies_isPaid);
    }

    public function render()
    {
        $companyEmails = Company::find($this->company->id)
            ->emails()
            ->paginate(20);
        $soldPolicies = []; //SoldPolicy::userData(searchText: $this->seachAllSoldPolicies)->ByCompany(company_id: $this->company->id)->paginate(8);


        $this->available_policies = SoldPolicy::when($this->seachAvailablePoliciesText, fn($q) => $q->searchByPolicyNumber($this->seachAvailablePoliciesText))
            ->byCompany(
                company_id: $this->company->id,
                is_paid: $this->availableSoldPolicies_isNotPaid === "0" ? null : false
            )
            ->only2025()
            ->paginate(5);

        return view('livewire.company-show', [
            'soldPolicies' => $soldPolicies,
            'companyEmails' => $companyEmails,
            'available_policies' => $this->available_policies,
        ]);
    }
}
