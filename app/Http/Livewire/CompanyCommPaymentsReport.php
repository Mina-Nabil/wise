<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Company;
use Livewire\Component;
use App\Models\Payments\CompanyCommPayment;
use App\Models\Payments\CommProfile;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

class CompanyCommPaymentsReport extends Component
{
    use WithPagination, ToggleSectionLivewire;

    public $selectedCompany = null;
    public $searchText;

    public $startSection = false;
    public $expirySection = false;
    public $companySection = false;
    public $issuedSection = false;
    public $statusesSection = false;
    public $typesSection = false;
    public $paymentDateSection = false;

    public $types = [];
    public $company_ids = [];
    public $start_from;
    public $start_to;
    public $issued_from;
    public $issued_to;
    public $expiry_from;
    public $expiry_to;
    public $payment_date_from;
    public $payment_date_to;
    public $statuses = [];
    public $is_renewal;

    public $Ecompany_ids = [];
    public $Etypes = [];
    public $Eis_renewal;
    public $Estart_from;
    public $Estart_to;
    public $Eissued_from;
    public $Eissued_to;
    public $Eexpiry_from;
    public $Eexpiry_to;
    public $Epayment_date_from;
    public $Epayment_date_to;
    public $Estatuses = [];

    public $sortColumn;
    public $sortDirection = 'asc';

    public $companies;
    public $searchCompany;

    public function mount()
    {
        $this->companies = Company::select('id', 'name')->get();
    }

    public function toggleStartDate()
    {
        $this->startSection = !$this->startSection;
    }

    public function toggleExpiryDate()
    {
        $this->expirySection = !$this->expirySection;
    }

    public function toggleIssuedDate()
    {
        $this->issuedSection = !$this->issuedSection;
    }

    public function togglePaymentDate()
    {
        $this->paymentDateSection = !$this->paymentDateSection;
    }

    public function toggleCompany()
    {
        $this->companySection = !$this->companySection;
    }

    public function toggleStatuses()
    {
        $this->statusesSection = !$this->statusesSection;
    }

    public function toggleTypes()
    {
        $this->typesSection = !$this->typesSection;
    }

    public function toggleRenewal()
    {
        $this->is_renewal = $this->is_renewal ? null : true;
    }

    public function setStartDates()
    {
        $this->start_from = $this->Estart_from;
        $this->start_to = $this->Estart_to;
        $this->startSection = false;
        $this->resetPage();
    }

    public function setExpiryDates()
    {
        $this->expiry_from = $this->Eexpiry_from;
        $this->expiry_to = $this->Eexpiry_to;
        $this->expirySection = false;
        $this->resetPage();
    }

    public function setIssuedDates()
    {
        $this->issued_from = $this->Eissued_from;
        $this->issued_to = $this->Eissued_to;
        $this->issuedSection = false;
        $this->resetPage();
    }

    public function setPaymentDates()
    {
        $this->payment_date_from = $this->Epayment_date_from;
        $this->payment_date_to = $this->Epayment_date_to;
        $this->paymentDateSection = false;
        $this->resetPage();
    }

    public function setCompanies()
    {
        $this->company_ids = $this->Ecompany_ids;
        $this->companySection = false;
        $this->resetPage();
    }

    public function setStatuses()
    {
        $this->statuses = $this->Estatuses;
        $this->statusesSection = false;
        $this->resetPage();
    }

    public function setTypes()
    {
        $this->types = $this->Etypes;
        $this->typesSection = false;
        $this->resetPage();
    }

    public function sortByColumn($column)
    {
        $this->sortColumn = $column;
        if ($this->sortDirection === 'asc') {
            $this->sortDirection = 'desc';
        } else {
            $this->sortDirection = 'asc';
        }
    }

    public function redirectToShowPage($id)
    {
        $this->dispatchBrowserEvent('openNewTab', ['url' => route('sold.policy.show', ['id' => $id])]);
    }

    public function updatingSearchText()
    {
        $this->resetPage();
    }

    public function exportReport()
    {
        // Handle company filter
        $selectedCompany = null;
        if (count($this->company_ids) > 0) {
            $selectedCompany = Company::find($this->company_ids[0]);
        }

        return CompanyCommPayment::exportReport(
            $this->is_renewal,
            $this->start_from ? Carbon::createFromFormat('Y-m-d', $this->start_from) : null,
            $this->start_to ? Carbon::createFromFormat('Y-m-d', $this->start_to) : null,
            $this->expiry_from ? Carbon::createFromFormat('Y-m-d', $this->expiry_from) : null,
            $this->expiry_to ? Carbon::createFromFormat('Y-m-d', $this->expiry_to) : null,
            $this->issued_from ? Carbon::createFromFormat('Y-m-d', $this->issued_from) : null,
            $this->issued_to ? Carbon::createFromFormat('Y-m-d', $this->issued_to) : null,
            $selectedCompany,
            $this->searchText,
            $this->statuses,
            $this->sortColumn,
            $this->sortDirection,
            $this->types,
            $this->payment_date_from ? Carbon::createFromFormat('Y-m-d', $this->payment_date_from) : null,
            $this->payment_date_to ? Carbon::createFromFormat('Y-m-d', $this->payment_date_to) : null
        );
    }

    public function render()
    {
        $STATUSES = CompanyCommPayment::PYMT_STATES;
        $Alltypes = ['cash', 'cheque', 'bank_transfer', 'visa']; // Basic payment types

        // Handle company filter
        $selectedCompany = null;
        if (count($this->company_ids) > 0) {
            $selectedCompany = Company::find($this->company_ids[0]);
        }

        $payments = CompanyCommPayment::report(
            $this->is_renewal,
            $this->start_from ? Carbon::createFromFormat('Y-m-d', $this->start_from) : null,
            $this->start_to ? Carbon::createFromFormat('Y-m-d', $this->start_to) : null,
            $this->expiry_from ? Carbon::createFromFormat('Y-m-d', $this->expiry_from) : null,
            $this->expiry_to ? Carbon::createFromFormat('Y-m-d', $this->expiry_to) : null,
            $this->issued_from ? Carbon::createFromFormat('Y-m-d', $this->issued_from) : null,
            $this->issued_to ? Carbon::createFromFormat('Y-m-d', $this->issued_to) : null,
            $selectedCompany,
            $this->searchText,
            $this->statuses,
            $this->sortColumn,
            $this->sortDirection,
            $this->types,
            $this->payment_date_from ? Carbon::createFromFormat('Y-m-d', $this->payment_date_from) : null,
            $this->payment_date_to ? Carbon::createFromFormat('Y-m-d', $this->payment_date_to) : null
        )->paginate(50);

        return view('livewire.company-comm-payments-report', [
            'STATUSES' => $STATUSES,
            'Alltypes' => $Alltypes,
            'payments' => $payments
        ]);
    }
} 