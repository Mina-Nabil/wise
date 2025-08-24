<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
use App\Models\Insurance\Company;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OutstandingSoldPolicyIndex extends Component
{

    use WithPagination, AlertFrontEnd, ToggleSectionLivewire, AuthorizesRequests;

    public $search;
    public $searchCompany;
    public $outstandingType = 'all';

    //policy start filter
    public $startSection = false;
    public $start_from;
    public $start_to;
    public $Estart_from;
    public $Estart_to;

    // Add payment date filter properties
    public $paymentSection = false;
    public $payment_from;
    public $payment_to;
    public $Epayment_from;
    public $Epayment_to;

    public $hasInvoiceFilter = null;

    // Add invoice payment date filter properties
    public $invoicePaymentSection = false;
    public $invoice_payment_from;
    public $invoice_payment_to;
    public $Einvoice_payment_from;
    public $Einvoice_payment_to;

    public $invoicePaidFilter = null;

    //company filter
    public $companySection = false;
    public $Ecompany_ids = [];
    public $company_ids = [];
    public $selectAllCompanies = false;

    public function toggleCompany()
    {
        $this->toggle($this->companySection);
        if ($this->companySection) {
            $this->Ecompany_ids = $this->company_ids;
            $this->checkSelectAllState();
        }
    }

    public function setCompany()
    {
        $this->company_ids = $this->Ecompany_ids;
        $this->toggleCompany();
    }

    public function clearCompany()
    {
        $this->company_ids = [];
        $this->Ecompany_ids = [];
        $this->selectAllCompanies = false;
    }

    public function toggleSelectAllCompanies()
    {
        $this->selectAllCompanies = !$this->selectAllCompanies;
        
        if ($this->selectAllCompanies) {
            $this->Ecompany_ids = Company::when($this->searchCompany, function ($query) {
                return $query->where('name', 'like', '%' . $this->searchCompany . '%');
            })->pluck('id')->toArray();
        } else {
            $this->Ecompany_ids = [];
        }
    }

    public function checkSelectAllState()
    {
        $allCompanyIds = Company::when($this->searchCompany, function ($query) {
            return $query->where('name', 'like', '%' . $this->searchCompany . '%');
        })->pluck('id')->toArray();
        
        $this->selectAllCompanies = !empty($allCompanyIds) && 
            count(array_intersect($allCompanyIds, $this->Ecompany_ids)) === count($allCompanyIds);
    }

    public function updatedSearchCompany()
    {
        $this->checkSelectAllState();
    }

    public function updatedEcompanyIds()
    {
        $this->checkSelectAllState();
    }

    public function toggleStartDate()
    {
        $this->toggle($this->startSection);
        if ($this->startSection) {
            $this->Estart_from = Carbon::parse($this->start_from)->toDateString();
            $this->Estart_to = Carbon::parse($this->start_to)->toDateString();
        }
    }

    public function setStartDates()
    {
        $this->start_from = Carbon::parse($this->Estart_from);
        $this->start_to = Carbon::parse($this->Estart_to);
        $this->toggle($this->startSection);
    }

    public function clearStartDates()
    {
        $this->start_from = null;
        $this->start_to = null;
    }

    public function togglePaymentDate()
    {
        $this->toggle($this->paymentSection);
        if ($this->paymentSection) {
            $this->Epayment_from = Carbon::parse($this->payment_from)->toDateString();
            $this->Epayment_to = Carbon::parse($this->payment_to)->toDateString();
        }
    }

    public function setPaymentDates()
    {
        $this->payment_from = Carbon::parse($this->Epayment_from);
        $this->payment_to = Carbon::parse($this->Epayment_to);
        $this->toggle($this->paymentSection);
    }

    public function clearPaymentDates()
    {
        $this->payment_from = null;
        $this->payment_to = null;
    }

    public function toggleHasInvoice()
    {
        $this->hasInvoiceFilter = !$this->hasInvoiceFilter;
    }

    public function clearHasInvoice()
    {
        $this->hasInvoiceFilter = null;
    }

    public function toggleInvoicePaymentDate()
    {
        $this->toggle($this->invoicePaymentSection);
        if ($this->invoicePaymentSection) {
            $this->Einvoice_payment_from = Carbon::parse($this->invoice_payment_from)->toDateString();
            $this->Einvoice_payment_to = Carbon::parse($this->invoice_payment_to)->toDateString();
        }
    }

    public function setInvoicePaymentDates()
    {
        $this->invoice_payment_from = Carbon::parse($this->Einvoice_payment_from);
        $this->invoice_payment_to = Carbon::parse($this->Einvoice_payment_to);
        $this->toggle($this->invoicePaymentSection);
    }

    public function clearInvoicePaymentDates()
    {
        $this->invoice_payment_from = null;
        $this->invoice_payment_to = null;
    }

    public function toggleInvoicePaid()
    {
        $this->invoicePaidFilter = !$this->invoicePaidFilter;
    }

    public function clearInvoicePaid()
    {
        $this->invoicePaidFilter = null;
    }

    public function exportReport()
    {
        if ($this->outstandingType === 'all') {
            $client_outstanding = false;
            $commission_outstanding = false;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'policy') {
            $client_outstanding = true;
            $commission_outstanding = false;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'commission') {
            $client_outstanding = false;
            $commission_outstanding = true;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'invoice') {
            $client_outstanding = false;
            $commission_outstanding = false;
            $invoice_outstanding = true;
        }

        return SoldPolicy::exportOutstanding(
            $this->search,
            $commission_outstanding,
            $client_outstanding,
            $invoice_outstanding,
            $this->start_from,
            $this->start_to,
            $this->company_ids,
            $this->payment_from,
            $this->payment_to,
            $this->hasInvoiceFilter,
            $this->invoice_payment_from,
            $this->invoice_payment_to,
            $this->invoicePaidFilter
        );
    }

    public function mount()
    {
        $this->authorize('viewReports', SoldPolicy::class);
        $this->payment_from = Carbon::now()->startOfMonth();
        $this->payment_to = Carbon::now()->endOfMonth();
    }

    public function render()
    {
        if ($this->outstandingType === 'all') {
            $client_outstanding = false;
            $commission_outstanding = false;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'policy') {
            $client_outstanding = true;
            $commission_outstanding = false;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'commission') {
            $client_outstanding = false;
            $commission_outstanding = true;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'invoice') {
            $client_outstanding = false;
            $commission_outstanding = false;
            $invoice_outstanding = true;
        }

        $soldPolicies = SoldPolicy::outstandingPolicies(
            $this->search,
            $commission_outstanding,
            $client_outstanding,
            $invoice_outstanding,
            $this->start_from,
            $this->start_to,
            $this->company_ids,
            $this->payment_from,
            $this->payment_to,
            $this->hasInvoiceFilter,
            $this->invoice_payment_from,
            $this->invoice_payment_to,
            $this->invoicePaidFilter
        )->simplePaginate(10);

        return view('livewire.outstanding-sold-policy-index', [
            'soldPolicies' => $soldPolicies,
            'companies' =>  Company::when($this->searchCompany, function ($query) {
                return $query->where('name', 'like', '%' . $this->searchCompany . '%');
            })->get()
        ]);
    }
}
