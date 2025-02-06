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
        if ($status == 'all') $this->filteredStatus = [];
        else $this->filteredStatus = [$status];
    }

    public function redirectToShowPage($id)
    {
        return redirect(route('sold.policy.show', ['id' => $id]));
    }

    //reseting page while searching
    public function updatingSearchText()
    {
        $this->resetPage();
    }

    public function render()
    {
        $statuses = ClientPayment::PYMT_STATES;
        $payments = ClientPayment::userData($this->filteredStatus, $this->myPayments, $this->searchText)
        ->with('sold_policy', 'sold_policy.client', 'sold_policy.creator', 'assigned', 'sold_policy.active_sales_comms', 'sold_policy.active_sales_comms.comm_profile')->paginate(50);
        return view('livewire.client-payment-index', [
            'statuses' => $statuses,
            'payments' => $payments
        ]);
    }
}
