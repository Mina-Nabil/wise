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
    public $types = [];
    public $section = 'all';
    
    // Add date filter properties
    public $dateSection = false;
    public $date_from;
    public $date_to;
    public $Edate_from;
    public $Edate_to;

    protected $queryString = ['section'];

    public function toggleDateFilter()
    {
        $this->dateSection = !$this->dateSection;
        if ($this->dateSection) {
            $this->Edate_from = $this->date_from ? Carbon::parse($this->date_from)->toDateString() : null;
            $this->Edate_to = $this->date_to ? Carbon::parse($this->date_to)->toDateString() : null;
        }
    }

    public function setDateFilter()
    {
        $this->date_from = $this->Edate_from ? Carbon::parse($this->Edate_from) : null;
        $this->date_to = $this->Edate_to ? Carbon::parse($this->Edate_to) : null;
        $this->dateSection = false;
    }

    public function clearDateFilter()
    {
        $this->date_from = null;
        $this->date_to = null;
    }

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

    public function render()
    {
        $statuses = ClientPayment::PYMT_STATES;
        $PYMT_TYPES = ClientPayment::PYMT_TYPES;
        
        $payments = ClientPayment::userData($this->filteredStatus, $this->myPayments, $this->searchText)
            ->when(count($this->types), fn($q) => $q->byTypes($this->types))
            ->when($this->date_from || $this->date_to, fn($q) => $q->byDateRange($this->date_from, $this->date_to))
            ->with('sold_policy', 'sold_policy.client', 'sold_policy.creator', 'assigned')
            ->paginate(50);

        return view('livewire.client-payment-index', [
            'statuses' => $statuses,
            'PYMT_TYPES' => $PYMT_TYPES,
            'payments' => $payments
        ]);
    }
}
