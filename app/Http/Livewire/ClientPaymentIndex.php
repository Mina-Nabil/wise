<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\ClientPayment;
use Livewire\WithPagination;

class ClientPaymentIndex extends Component
{
    use WithPagination;

    public $filteredStatus = [];
    public $myPayments = false;
    public $searchText;
    public $dateRange;

    public function filterByStatus($status)
    {
        $this->filteredStatus = [$status];
    }

    public function render()
    {
        $statuses = ClientPayment::PYMT_STATES;
        $payments = ClientPayment::userData($this->filteredStatus, $this->myPayments)->paginate(50);
        return view('livewire.client-payment-index', [
            'statuses' => $statuses,
            'payments' => $payments
        ]);
    }
}
