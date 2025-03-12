<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Company;
use App\Models\Payments\Invoice;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class InvoicesReport extends Component
{
    use WithPagination, ToggleSectionLivewire;

    public $searchText;
    public $company_ids = [];
    public $created_from;
    public $created_to;

    // Edit sections
    public $createdSection = false;
    public $companySection = false;
    
    // Edit variables
    public $Ecompany_ids = [];
    public $Ecreated_from;
    public $Ecreated_to;

    public $sortColumn;
    public $sortDirection = 'asc';

    public $companies;
    public $searchCompany;

    public function sortByColumn($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleCreatedDate()
    {
        $this->toggle($this->createdSection);
        if ($this->createdSection) {
            $this->Ecreated_from = Carbon::parse($this->created_from)->toDateString();
            $this->Ecreated_to = Carbon::parse($this->created_to)->toDateString();
        }
    }

    public function setCreatedDates()
    {
        $this->created_from = Carbon::parse($this->Ecreated_from);
        $this->created_to = Carbon::parse($this->Ecreated_to);
        $this->toggle($this->createdSection);
    }

    public function clearCreatedDates()
    {
        $this->created_from = null;
        $this->created_to = null;
    }

    public function toggleCompany()
    {
        $this->toggle($this->companySection);
        if ($this->companySection) {
            $this->Ecompany_ids = $this->company_ids;
        }
    }

    public function pushCompany($id)
    {
        $this->Ecompany_ids[] = $id;
    }

    public function setCompany()
    {
        $this->company_ids = $this->Ecompany_ids;
        $this->toggleCompany();
    }

    public function clearCompany()
    {
        $this->company_ids = [];
    }

    public function updatedSearchCompany()
    {
        $this->companies = Company::when(
            $this->searchCompany, 
            fn($q) => $q->SearchBy($this->searchCompany)
        )->get()->take(5);
    }

    public function updatingSearchText()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->companies = Company::when(
            $this->searchCompany, 
            fn($q) => $q->SearchBy($this->searchCompany)
        )->get()->take(5);
    }

    public function render()
    {
        $invoices = Invoice::with(['creator', 'commissions', 'company'])
            ->when($this->created_from, function($query) {
                $query->whereDate('created_at', '>=', $this->created_from);
            })
            ->when($this->created_to, function($query) {
                $query->whereDate('created_at', '<=', $this->created_to);
            })
            ->when($this->company_ids, function($query) {
                $query->whereIn('company_id', $this->company_ids);
            })
            ->when($this->searchText, function($query) {
                $query->where(function($q) {
                    $q->where('serial', 'like', "%{$this->searchText}%")
                      ->orWhereHas('creator', function($q) {
                          $q->where('first_name', 'like', "%{$this->searchText}%")
                            ->orWhere('last_name', 'like', "%{$this->searchText}%");
                      });
                });
            })
            ->when($this->sortColumn, function($query) {
                $query->orderBy($this->sortColumn, $this->sortDirection);
            }, function($query) {
                $query->latest();
            })
            ->paginate(50);

        return view('livewire.invoices-report', [
            'invoices' => $invoices
        ]);
    }
} 