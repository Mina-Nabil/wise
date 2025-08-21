<?php

namespace App\Http\Livewire;

use App\Models\Business\SoldPolicy;
use App\Models\Insurance\Company;
use Livewire\Component;
use App\Models\Payments\ClientPayment;
use App\Traits\AlertFrontEnd;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

class ClientPaymentFinance extends Component
{
    use WithPagination, AlertFrontEnd, AuthorizesRequests;

    public $filteredStatus = ClientPayment::NOT_PAID_STATES;
    public $selectedCompany = null;
    public $isDuePassed = false;
    public $dueDays;
    public $myPayments = false;
    public $searchText;

    public $sortColomn;
    public $sortDirection = 'asc';


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


    /////edit note section
    public $noteSection;
    public $note;

    public function openNoteSection($id)
    {
        $this->noteSection = ClientPayment::findOrFail($id);
        $this->note = $this->noteSection->finance_note;
    }

    public function closeNoteSection()
    {
        $this->reset(['noteSection', 'note']);
    }

    public function setNote()
    {
        $res = $this->noteSection->setFinanceNote($this->note);
        if ($res) {
            $this->closeNoteSection();
            $this->alert('success', 'Note updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }



    public function render()
    {
        $this->authorize('viewReports', SoldPolicy::class);
        $statuses = ClientPayment::PYMT_STATES;
        $companies = Company::all();
        $payments = ClientPayment::userData(states: $this->filteredStatus, searchText: $this->searchText)->includeDue()
            ->when($this->selectedCompany, fn($q) => $q->byCompany($this->selectedCompany->id))
            ->when($this->dueDays && !$this->isDuePassed, fn($q) => $q->dueAfter($this->dueDays))
            ->when($this->dueDays && $this->isDuePassed, fn($q) => $q->duePassed($this->dueDays))
            ->when($this->sortColomn === 'due' , fn($q) => $q->SortByDue(sort:$this->sortDirection))
            ->when($this->sortColomn === 'start' , fn($q) => $q->SortByPolicyStart(sort:$this->sortDirection))
            // ->sortByDue()
            ->with('sold_policy', 'sold_policy.client', 'sold_policy.creator', 'assigned');
        $payments =    $payments->paginate(50);
        return view('livewire.client-payment-finance', [
            'statuses' => $statuses,
            'companies' => $companies,
            'payments' => $payments
        ]);
    }
}
