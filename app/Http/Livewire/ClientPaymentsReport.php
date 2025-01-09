<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Company;
use Livewire\Component;
use App\Models\Payments\ClientPayment;
use App\Models\Payments\CommProfile;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

class ClientPaymentsReport extends Component
{
    use WithPagination, ToggleSectionLivewire;

    public $selectedCompany = null;
    public $isDuePassed = false;
    public $dueDays;
    public $myPayments = false;
    public $searchText;

    public $startSection = false;
    public $expirySection = false;
    public $companySection = false;
    public $issuedSection = false;
    public $statusesSection = false;
    public $typesSection = false;

    public $types = [];
    public $company_ids = [];
    public $start_from;
    public $start_to;
    public $issued_from;
    public $issued_to;
    public $expiry_from;
    public $expiry_to;
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
    public $Estatuses = [];

    public $sortColomn;
    public $sortDirection = 'asc';

    public $companies;
    public $searchCompany;

    public $salesOutSection = false;
    public $sales_outs;
    public $searchSalesOut;
    public $sales_out_ids = [];
    public $Esales_out_ids = [];

    public function sortByColomn($colomn)
    {
        $this->sortColomn = $colomn;
        if ($this->sortDirection) {
            if ($this->sortDirection === 'asc') {
                $this->sortDirection = 'desc';
            } else {
                $this->sortDirection = 'asc';
            }
        }
    }

    public function redirectToShowPage($id)
    {
        $this->dispatchBrowserEvent('openNewTab', ['url' => route('sold.policy.show', ['id' => $id])]);
    }

    public function filterByStatus($status)
    {
        if ($status == 'all') $this->statuses = [];
        else if ($status == 'not_paid') $this->statuses = ClientPayment::NOT_PAID_STATES;
        else $this->statuses = [$status];
    }

    public function filterByCompany($company_id = null)
    {
        if ($company_id)
            $this->selectedCompany = Company::find($company_id);
        else $this->selectedCompany = null;
    }


    public function toggleExpiryDate()
    {
        $this->toggle($this->expirySection);
        if ($this->expirySection) {
            $this->Eexpiry_from = Carbon::parse($this->expiry_from)->toDateString();
            $this->Eexpiry_to = Carbon::parse($this->expiry_to)->toDateString();
        }
    }

    public function setExpiryDates()
    {
        $this->expiry_from = Carbon::parse($this->Eexpiry_from);
        $this->expiry_to = Carbon::parse($this->Eexpiry_to);
        $this->toggle($this->expirySection);
    }

    public function clearExpiryDates()
    {
        $this->expiry_from = null;
        $this->expiry_to = null;
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

    public function toggleIssuedDate()
    {
        $this->toggle($this->issuedSection);
        if ($this->issuedSection) {
            $this->Eissued_from = Carbon::parse($this->issued_from)->toDateString();
            $this->Eissued_to = Carbon::parse($this->issued_to)->toDateString();
        }
    }

    public function setIssuedDates()
    {
        $this->issued_from = Carbon::parse($this->Eissued_from);
        $this->issued_to = Carbon::parse($this->Eissued_to);
        $this->toggle($this->issuedSection);
    }

    public function clearIssuedDates()
    {
        $this->issued_from = null;
        $this->issued_to = null;
    }

    public function toggleTypes()
    {
        $this->toggle($this->typesSection);
        if ($this->typesSection) {
            $this->Etypes = $this->types;
        }
    }

    public function pushType($id)
    {
        $this->Etypes[] = $id;
    }

    public function setTypes()
    {
        $this->types = $this->Etypes;
        $this->toggleTypes();
    }

    public function clearTypes()
    {
        $this->types = [];
    }


    public function toggleCompany()
    {
        $this->toggle($this->companySection);
        if ($this->companySection) {
            $this->Ecompany_ids = $this->company_ids;
        }
    }

    public function pushCompany($id)
    {
        $this->Ecompany_ids[] = $id;
    }

    public function setCompany()
    {
        $this->company_ids = $this->Ecompany_ids;
        $this->toggleCompany();
    }

    public function clearCompany()
    {
        $this->company_ids = [];
    }

    public function updatedSearchCompany()
    {
        $this->companies = Company::when($this->searchCompany, fn($q) => $q->SearchBy($this->searchCompany))->get()->take(5);
    }

    public function pushSalesOut($id)
    {
        $this->Esales_out_ids[] = $id;
    }

    public function setSalesOuts()
    {
        $this->sales_out_ids = $this->Esales_out_ids;
        $this->toggleSalesOut();
    }

    public function updatedSearchSalesOut()
    {
        $this->sales_outs = CommProfile::when(fn($q) => $q->where('title', 'like', '%' . $this->searchSalesOut . '%'))
            ->take(5)
            ->get();
    }

    public function togglestatuses()
    {
        $this->toggle($this->statusesSection);
        if ($this->statusesSection) {
            $this->Estatuses = $this->statuses;
        }
    }

    public function clearstatuses()
    {
        $this->statuses = [];
    }

    public function setStatuses()
    {
        $this->statuses = $this->Estatuses;
        $this->toggle($this->statusesSection);
    }

    public function toggleSalesOut()
    {
        $this->toggle($this->salesOutSection);
        if ($this->salesOutSection) {
            $this->Esales_out_ids = $this->sales_out_ids;
        }
    }

    public function clearSalesOuts()
    {
        $this->sales_out_ids = [];
    }

    public function clearrenwal()
    {
        $this->is_renewal = null;
    }
    public function toggleRenewal()
    {
        $this->toggle($this->is_renewal);
    }

    public function exportReport()
    {

        return ClientPayment::exportReport(
            $this->is_renewal,
            $this->start_from,
            $this->start_to,
            $this->expiry_from,
            $this->expiry_to,
            $this->issued_from,
            $this->issued_to,
            $this->selectedCompany,
            $this->searchText,
            $this->sales_out_ids,
            $this->statuses,
            $this->sortColomn,
            $this->sortDirection
        );
    }



    //reseting page while searching
    public function updatingSearchText()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->companies = Company::when($this->searchCompany, fn($q) => $q->SearchBy($this->searchCompany))->get()->take(5);
        $this->sales_outs = CommProfile::when($this->searchSalesOut, fn($q) => $q->where('title', 'like', '%' . $this->searchSalesOut . '%'))
            ->take(5)->get();
    }

    public function render()
    {
        $STATUSES = ClientPayment::PYMT_STATES;
        $Alltypes = ClientPayment::PYMT_TYPES;

        $payments = ClientPayment::report(
            $this->is_renewal,
            $this->start_from,
            $this->start_to,
            $this->expiry_from,
            $this->expiry_to,
            $this->issued_from,
            $this->issued_to,
            $this->selectedCompany,
            $this->searchText,
            $this->sales_out_ids,
            $this->statuses,
            $this->sortColomn,
            $this->sortDirection
        );
        $payments =    $payments->paginate(50);
        return view('livewire.client-payments-report', [
            'STATUSES' => $STATUSES,
            'Alltypes' => $Alltypes,
            'payments' => $payments
        ]);
    }
}
