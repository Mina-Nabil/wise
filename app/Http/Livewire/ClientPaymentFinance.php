<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\ClientPayment;
use Livewire\WithPagination;

class ClientPaymentFinance extends Component
{
    use WithPagination;

    public $filteredStatus = ClientPayment::NOT_PAID_STATES;
    public $isDueAfter = true;
    public $dueDays;
    public $myPayments = false;
    public $searchText;
    public $dateRange;

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


    public function render()
    {
        $statuses = ClientPayment::PYMT_STATES;
        $payments = ClientPayment::includeDue()
            ->when($this->dueDays && $this->isDueAfter, fn($q) => $q->dueAfter($this->dueDays))
            ->when($this->dueDays && !$this->isDueAfter, fn($q) => $q->duePassed($this->dueDays))
            ->searchBy($this->searchText)
            ->FilterByStates($this->filteredStatus)
            ->with('sold_policy', 'sold_policy.client', 'sold_policy.creator', 'assigned')->paginate(50);
        return view('livewire.client-payment-finance', [
            'statuses' => $statuses,
            'payments' => $payments
        ]);
    }
}
