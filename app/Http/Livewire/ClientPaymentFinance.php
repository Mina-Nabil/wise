<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\ClientPayment;
use Livewire\WithPagination;

class ClientPaymentFinance extends Component
{
    use WithPagination;

    public $filteredStatus = [];
    public $dueAfter;
    public $myPayments = false;
    public $searchText;
    public $dateRange;
    public $notPaidOnly = true;

    public function redirectToShowPage($id)
    {
        $this->dispatchBrowserEvent('openNewTab', ['url' => route('sold.policy.show', ['id' => $id])]);

    }

    public function filterByStatus($status)
    {
        if ($status == 'all') $this->filteredStatus = [];
        else $this->filteredStatus = [$status];
    }


    public function render()
    {
        $statuses = ClientPayment::PYMT_STATES;
        $payments = ClientPayment::when($this->dueAfter, fn($q) => $q->dueAfter($this->dueAfter))
            ->searchBy($this->searchText)
            ->FilterByStates($this->filteredStatus)
            ->when($this->notPaidOnly, fn($q) => $q->notPaidOnly())
            ->with('sold_policy', 'sold_policy.client', 'sold_policy.creator', 'assigned')->paginate(50);
        return view('livewire.client-payment-finance', [
            'statuses' => $statuses,
            'payments' => $payments
        ]);
    }
}
