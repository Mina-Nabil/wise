<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\ClientPayment;
use Livewire\WithPagination;
use Carbon\Carbon;

class ClientPaymentIndex extends Component
{
    use WithPagination;

    public $filteredStatus = [];
    public $myPayments = false;
    public $searchText;
    public $dateRange;
    public $startDate;
    public $endDate;
    public $types = [];
    public $section = 'all';
    

    protected $queryString = [
        'section',
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function getTypeIcon($type)
    {
        return match($type) {
            ClientPayment::PYMT_TYPE_CASH => 'heroicons-outline:cash',
            ClientPayment::PYMT_TYPE_CHEQUE => 'heroicons-outline:document-text',
            ClientPayment::PYMT_TYPE_BANK_TRNSFR => 'heroicons-outline:credit-card',
            ClientPayment::PYMT_TYPE_VISA => 'cib:cc-visa',
            ClientPayment::PYMT_TYPE_SALES_OUT => 'heroicons-outline:shopping-cart',
            default => 'heroicons-outline:collection'
        };
    }

    public function changeSection($section)
    {
        $this->section = $section;
        if ($section === 'all') {
            $this->types = [];
        } else {
            $this->types = [$section];
        }
    }

    public function filterByStatus($status)
    {
        if ($status == 'all') $this->filteredStatus = [];
        else $this->filteredStatus = [$status];
    }

    public function filterByType($type)
    {
        if ($type == 'all') $this->types = [];
        else $this->types = [$type];
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

    public function updatedDateRange()
    {
        if (strpos($this->dateRange, 'to') !== false) {
            // The string contains 'to'
            [$this->startDate, $this->endDate] = explode(' to ', $this->dateRange);
        }
    }

    public function render()
    {
        $statuses = ClientPayment::PYMT_STATES;
        $PYMT_TYPES = ClientPayment::PYMT_TYPES;
        
        $paymentsQuery = ClientPayment::userData($this->filteredStatus, $this->myPayments, $this->searchText)
            ->when(count($this->types), fn($q) => $q->byTypes($this->types))
            ->when($this->startDate && $this->endDate, function ($query) {
                $startDate = $this->startDate ? Carbon::parse($this->startDate) : null;
                $endDate = $this->endDate ? Carbon::parse($this->endDate) : null;
                return $query->soldPolicyByDateRange($startDate, $endDate);
            })
            ->with('sold_policy', 'sold_policy.client', 'sold_policy.creator', 'assigned');

        $totalPayments = $paymentsQuery->clone()->get()->sum('amount');
        $payments = $paymentsQuery->paginate(50);

        return view('livewire.client-payment-index', [
            'statuses' => $statuses,
            'PYMT_TYPES' => $PYMT_TYPES,
            'payments' => $payments,
            'totalPayments' => $totalPayments
        ]);
    }
}
