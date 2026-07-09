<?php

namespace App\Http\Livewire;

use App\Models\Business\SoldPolicy;
use App\Models\Insurance\Company;
use App\Models\Insurance\Policy;
use App\Models\Payments\CommProfile;
use App\Models\Users\User;
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
    public $selectedPolicy = null;
    public $selectedMainSales = null;
    public $selectedCommProfile = null;
    public $isDuePassed = false;
    public $dueDays;
    public $searchConfiguration;
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

    public function filterByPolicy($policy_id = null)
    {
        if ($policy_id)
            $this->selectedPolicy = Policy::find($policy_id);
        else $this->selectedPolicy = null;
    }

    public function filterByMainSales($user_id = null)
    {
        if ($user_id)
            $this->selectedMainSales = User::find($user_id);
        else $this->selectedMainSales = null;
    }

    public function filterByCommProfile($comm_profile_id = null)
    {
        if ($comm_profile_id)
            $this->selectedCommProfile = CommProfile::find($comm_profile_id);
        else $this->selectedCommProfile = null;
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
        $policies = Policy::all();
        $mainSalesUsers = User::active()->get();
        $commProfiles = CommProfile::select('id', 'title')->get();
        $payments = ClientPayment::userData(states: $this->filteredStatus, searchText: $this->searchText)->includeDue($this->searchConfiguration)
            ->when($this->selectedCompany, fn($q) => $q->byCompany($this->selectedCompany->id))
            ->when($this->selectedPolicy, fn($q) => $q->byPolicyId($this->selectedPolicy->id))
            ->when($this->selectedMainSales, fn($q) => $q->byMainSales($this->selectedMainSales->id))
            ->when($this->selectedCommProfile, fn($q) => $q->byCommProfileId($this->selectedCommProfile->id))
            ->when($this->dueDays && !$this->isDuePassed, fn($q) => $q->dueAfter($this->dueDays))
            ->when($this->dueDays && $this->isDuePassed, fn($q) => $q->duePassed($this->dueDays))
            ->when($this->sortColomn === 'due' , fn($q) => $q->SortByDue(sort:$this->sortDirection))
            ->when($this->sortColomn === 'start' , fn($q) => $q->SortByPolicyStart(sort:$this->sortDirection))
            // ->sortByDue()
            ->with('sold_policy', 'sold_policy.client', 'sold_policy.creator', 'sold_policy.policy', 'sold_policy.main_sales', 'sold_policy.sales_comms.comm_profile', 'assigned');
        $payments =    $payments->paginate(50);
        return view('livewire.client-payment-finance', [
            'statuses' => $statuses,
            'companies' => $companies,
            'policies' => $policies,
            'mainSalesUsers' => $mainSalesUsers,
            'commProfiles' => $commProfiles,
            'payments' => $payments
        ]);
    }
}
