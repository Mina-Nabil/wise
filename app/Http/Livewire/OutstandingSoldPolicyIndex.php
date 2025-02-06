<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
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


    public function render()
    {
        if ($this->outstandingType === 'all') {
            $client_outstanding = false;
            $commission_outstanding = false;
        } elseif ($this->outstandingType === 'policy') {
            $client_outstanding = true;
            $commission_outstanding = false;
        } elseif ($this->outstandingType === 'commission') {
            $client_outstanding = false;
            $commission_outstanding = true;
        }

        $soldPolicies = SoldPolicy::userData(searchText: $this->search, is_commission_outstanding: $commission_outstanding, is_client_outstanding: $client_outstanding)
            ->when($this->start_from && $this->start_to, function ($query) {
                $query->fromTo($this->start_from, $this->start_to);
            })
            ->with('last_company_comm_payment')
            ->paginate(20);

        return view('livewire.outstanding-sold-policy-index', [
            'soldPolicies' => $soldPolicies,
        ]);
    }
}
