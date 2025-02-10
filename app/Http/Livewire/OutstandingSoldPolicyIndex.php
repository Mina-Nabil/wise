<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
use App\Models\Insurance\Company;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;

class OutstandingSoldPolicyIndex extends Component
{

    use WithPagination, AlertFrontEnd, ToggleSectionLivewire;

    public $search;
    public $outstandingType = 'all';

    //policy start filter
    public $startSection = false;
    public $start_from;
    public $start_to;
    public $Estart_from;
    public $Estart_to;



    public function toggleStartDate()
    {
        $this->toggle($this->startSection);
        if ($this->startSection) {
            $this->Estart_from = Carbon::parse($this->start_from)->toDateString();
            $this->Estart_to = Carbon::parse($this->start_to)->toDateString();
        }
    }

    public function setStartDates()
    {
        $this->start_from = Carbon::parse($this->Estart_from);
        $this->start_to = Carbon::parse($this->Estart_to);
        $this->toggle($this->startSection);
    }

    public function clearStartDates()
    {
        $this->start_from = null;
        $this->start_to = null;
    }

    ///company filter
    public $companySection = false;
    public $Ecompany_ids = [];
    public $company_ids = [];

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

    public function exportReport()
    {
        if ($this->outstandingType === 'all') {
            $client_outstanding = false;
            $commission_outstanding = false;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'policy') {
            $client_outstanding = true;
            $commission_outstanding = false;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'commission') {
            $client_outstanding = false;
            $commission_outstanding = true;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'invoice') {
            $client_outstanding = false;
            $commission_outstanding = false;
            $invoice_outstanding = true;
        }

        return SoldPolicy::exportOutstanding(
            $this->search,
            $commission_outstanding,
            $client_outstanding,
            $invoice_outstanding,
            $this->start_from,
            $this->start_to,
            $this->company_ids
        );
    }



    public function render()
    {
        if ($this->outstandingType === 'all') {
            $client_outstanding = false;
            $commission_outstanding = false;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'policy') {
            $client_outstanding = true;
            $commission_outstanding = false;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'commission') {
            $client_outstanding = false;
            $commission_outstanding = true;
            $invoice_outstanding = false;
        } elseif ($this->outstandingType === 'invoice') {
            $client_outstanding = false;
            $commission_outstanding = false;
            $invoice_outstanding = true;
        }

        $soldPolicies = SoldPolicy::outstandingPolicies(
            $this->search,
            $commission_outstanding,
            $client_outstanding,
            $invoice_outstanding,
            $this->start_from,
            $this->start_to,
            $this->company_ids
        )->paginate(20);

        return view('livewire.outstanding-sold-policy-index', [
            'soldPolicies' => $soldPolicies,
            'companies' =>  Company::all()
        ]);
    }
}
