<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Company;
use Livewire\Component;
use App\Models\Payments\ClientPayment;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

class ClientPaymentFinance extends Component
{
    use WithPagination;

    public $filteredStatus = ClientPayment::NOT_PAID_STATES;
    public $selectedCompany = null;
    public $isDuePassed = false;
    public $dueDays;
    public $myPayments = false;
    public $searchText;

    public function redirectToShowPage($id)
    {
        $this->dispatchBrowserEvent('openNewTab', ['url' => route('sold.policy.show', ['id' => $id])]);
    }

    public function filterByStatus($status)
    {
        if ($status == 'all') $this->filteredStatus = [];
        else if ($status == 'not_paid') $this->filteredStatus = ClientPayment::NOT_PAID_STATES;
        else $this->filteredStatus = [$status];
    }

    public function filterByCompany($company_id = null)
    {
        if ($company_id)
            $this->selectedCompany = Company::find($company_id);
        else $this->selectedCompany = null;
    }

    //reseting page while searching
    public function updatingSearchText()
    {
        $this->resetPage();
    }
    public function updatingDueDays()
    {
        $this->resetPage();
    }
    public function updatingIsDuePassed()
    {
        $this->resetPage();
    }



    public function render()
    {
        $statuses = ClientPayment::PYMT_STATES;
        $companies = Company::all();
        $payments = ClientPayment::includeDue()
            ->when($this->selectedCompany, fn($q) => $q->byCompany($this->selectedCompany->id))
            ->when($this->dueDays && !$this->isDuePassed, fn($q) => $q->dueAfter($this->dueDays))
            ->when($this->dueDays && $this->isDuePassed, fn($q) => $q->duePassed($this->dueDays))
            ->when($this->searchText, fn($q) => $q->searchBy($this->searchText))
            ->when(count($this->filteredStatus), fn($q) => $q->FilterByStates($this->filteredStatus))
            ->with('sold_policy', 'sold_policy.client', 'sold_policy.creator', 'assigned');
        $payments =    $payments->paginate(50);
        return view('livewire.client-payment-finance', [
            'statuses' => $statuses,
            'companies' => $companies,
            'payments' => $payments
        ]);
    }
}
