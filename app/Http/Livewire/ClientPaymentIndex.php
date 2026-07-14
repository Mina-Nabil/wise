<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\ClientPayment;
use App\Models\Insurance\Company;
use App\Models\Payments\CommProfile;
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

    // new filters
    public $checkedFilter = 'all'; // all | checked | unchecked
    public $companyFilter;
    public $commProfileFilter;
    public $dueRange;
    public $dueStart;
    public $dueEnd;

    // sorting by collected date
    public $sortCollectedDir = 'desc';

    protected $queryString = [
        'section',
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function sortByCollected()
    {
        $this->sortCollectedDir = $this->sortCollectedDir === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    public function toggleChecked($id)
    {
        $payment = ClientPayment::find($id);
        if ($payment) {
            $payment->setChecked(!$payment->is_checked);
        }
    }

    public function updatedCheckedFilter()
    {
        $this->resetPage();
    }

    public function updatedCompanyFilter()
    {
        $this->resetPage();
    }

    public function updatedCommProfileFilter()
    {
        $this->resetPage();
    }

    public function updatedDueRange()
    {
        if (strpos($this->dueRange, 'to') !== false) {
            [$this->dueStart, $this->dueEnd] = explode(' to ', $this->dueRange);
            $this->resetPage();
        }
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
            ->when($this->dueStart && $this->dueEnd, function ($query) {
                $query->whereBetween('client_payments.due', [
                    Carbon::parse($this->dueStart)->format('Y-m-d 00:00:00'),
                    Carbon::parse($this->dueEnd)->format('Y-m-d 23:59:59'),
                ]);
            })
            ->when($this->checkedFilter === 'checked', fn($q) => $q->where('client_payments.is_checked', true))
            ->when($this->checkedFilter === 'unchecked', fn($q) => $q->where('client_payments.is_checked', false))
            ->when($this->companyFilter, fn($q) => $q->byCompany($this->companyFilter))
            ->when($this->commProfileFilter, fn($q) => $q->byCommProfileId($this->commProfileFilter))
            ->with('sold_policy', 'sold_policy.client', 'sold_policy.creator', 'sold_policy.policy.company', 'sold_policy.main_sales', 'assigned')
            ->orderBy('client_payments.collected_date', $this->sortCollectedDir === 'asc' ? 'asc' : 'desc');

        $totalPayments = $paymentsQuery->clone()->get()->sum('amount');
        $payments = $paymentsQuery->paginate(50);

        $companies = Company::orderBy('name')->get();
        $commProfiles = CommProfile::orderBy('title')->get();

        return view('livewire.client-payment-index', [
            'statuses' => $statuses,
            'PYMT_TYPES' => $PYMT_TYPES,
            'payments' => $payments,
            'totalPayments' => $totalPayments,
            'companies' => $companies,
            'commProfiles' => $commProfiles,
        ]);
    }
}
