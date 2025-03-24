<?php

namespace App\Http\Livewire;

use App\Models\Business\SoldPolicy;
use App\Models\Insurance\Company;
use App\Models\Insurance\CompanyEmail;
use App\Models\Insurance\InvoiceExtra;
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

    // Invoice extras properties
    public $newExtraSection = false;
    public $editExtraSection = false;
    public $extraTitle;
    public $extraAmount;
    public $extraNote;
    public $extraId;
    public $confirmDeleteExtraId = null;
    public $selectedExtras = []; // Array to store selected extra IDs

    protected $listeners = ['deleteInvoice', 'confirmInvoice', 'deleteExtra']; //functions need confirmation

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

    // Invoice Extras functions
    public function openNewExtraSection()
    {
        $this->newExtraSection = true;
        $this->resetExtraFields();
    }

    public function closeNewExtraSection()
    {
        $this->newExtraSection = false;
        $this->resetExtraFields();
    }

    public function resetExtraFields()
    {
        $this->extraTitle = '';
        $this->extraAmount = '';
        $this->extraNote = '';
        $this->extraId = null;
    }

    public function addExtra()
    {
        $this->validate([
            'extraTitle' => 'required|string|max:255',
            'extraAmount' => 'required|numeric|min:0',
            'extraNote' => 'nullable|string',
        ]);

        $res = InvoiceExtra::createNew(
            $this->company,
            $this->extraTitle,
            $this->extraAmount,
            $this->extraNote
        );

        if ($res) {
            $this->closeNewExtraSection();
            $this->alert('success', 'Extra added successfully');
        } else {
            $this->alert('failed', 'Failed to add extra');
        }
    }

    public function openEditExtraSection($id)
    {
        $extra = InvoiceExtra::find($id);
        if ($extra) {
            $this->extraId = $extra->id;
            $this->extraTitle = $extra->title;
            $this->extraAmount = $extra->amount;
            $this->extraNote = $extra->note;
            $this->editExtraSection = true;
        }
    }

    public function closeEditExtraSection()
    {
        $this->editExtraSection = false;
        $this->resetExtraFields();
    }

    public function updateExtra()
    {
        $this->validate([
            'extraTitle' => 'required|string|max:255',
            'extraAmount' => 'required|numeric|min:0',
            'extraNote' => 'nullable|string',
        ]);

        $extra = InvoiceExtra::find($this->extraId);
        if ($extra) {
            $res = $extra->editInfo(
                $this->extraTitle,
                $this->extraAmount,
                $this->extraNote
            );

            if ($res) {
                $this->closeEditExtraSection();
                $this->alert('success', 'Extra updated successfully');
            } else {
                $this->alert('failed', 'Failed to update extra');
            }
        }
    }

    public function confirmDeleteExtra($id)
    {
        $this->confirmDeleteExtraId = $id;
    }

    public function cancelDeleteExtra()
    {
        $this->confirmDeleteExtraId = null;
    }

    public function deleteExtra()
    {
        $extra = InvoiceExtra::find($this->confirmDeleteExtraId);
        if ($extra) {
            $res = $extra->deleteExtra();

            if ($res) {
                $this->confirmDeleteExtraId = null;
                $this->alert('success', 'Extra deleted successfully');
            } else {
                $this->alert('failed', 'Cannot delete extra that is linked to an invoice');
            }
        }
    }

    public function openConfirmInvoice($id = true)
    {
        $this->confirmInvoiceId = $id ?? true;
    }

    public function closeConfirmInvoice()
    {
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
        $this->selectedExtras = []; // Reset selected extras when opening new invoice
    }

    public function closeNewInvoiceSec()
    {
        $this->newInvoiceSection = false;
        $this->selectedExtras = []; // Reset selected extras when closing
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

    public function updatedNetTotal()
    {
        if ($this->net_total >= 0) {
            $this->gross_total = round($this->net_total / 0.95, 2);
            $this->tax_total = round($this->gross_total * 0.05, 2);
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

        $res = Invoice::newInvoice($this->company->id, $this->serial, $this->gross_total, $this->sold_policies_entries, $this->selectedExtras);

        if ($res) {
            $this->reset(['serial', 'gross_total', 'tax_total', 'sold_policies_entries', 'selectedExtras']);
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

    public function updatedSelectedExtras()
    {
        $this->updateTotal();
    }
    

    public function updateTotal()
    {
        $this->net_total = 0;
        foreach ($this->sold_policies_entries as $e) {
            $this->net_total += is_numeric($e['amount']) ? $e['amount'] : 0;
        }
        foreach ($this->selectedExtras as $e) {
            $extra = InvoiceExtra::find($e);
            $this->net_total += is_numeric($extra->amount) ? $extra->amount : 0;
        }
        $this->updatedNetTotal();
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
            
        $invoiceExtras = Company::find($this->company->id)
            ->invoiceExtras()
            ->paginate(20);
            
        $soldPolicies = []; //SoldPolicy::userData(searchText: $this->seachAllSoldPolicies)->ByCompany(company_id: $this->company->id)->paginate(8);


        $this->available_policies = SoldPolicy::when($this->seachAvailablePoliciesText, fn($q) => $q->searchByPolicyNumber($this->seachAvailablePoliciesText))
            ->byCompany(
                company_id: $this->company->id,
                is_paid: $this->availableSoldPolicies_isNotPaid === "0" ? null : false
            )
            // ->only2025()
            // ->orwhere('sold_policies.id', 1798)
            // ->orwhere('sold_policies.id', 2092)
            // ->orwhere('sold_policies.id', 1015)
            // ->orwhere('sold_policies.id', 2334)
            ->paginate(8);

        return view('livewire.company-show', [
            'soldPolicies' => $soldPolicies,
            'companyEmails' => $companyEmails,
            'available_policies' => $this->available_policies,
            'invoiceExtras' => $invoiceExtras,
        ]);
    }
}
