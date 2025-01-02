<?php

namespace App\Http\Livewire;

use App\Models\Corporates\Corporate;
use App\Models\Customers\Customer;
use App\Models\Customers\Followup;
use App\Models\Insurance\Policy;
use App\Models\Users\User;
use Livewire\Component;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Livewire\WithPagination;

class FollowupReport extends Component
{
    use WithPagination, ToggleSectionLivewire;

    public $callTimeSection = false;
    public $EcallTime_from;
    public $callTime_from;
    public $EcallTime_to;
    public $callTime_to;

    public $lobSection;
    public $line_of_business;
    public $Eline_of_business;

    public $salesSection;
    public $ESalesId;
    public $salesId;
    public $SalesName;

    public $calledType;
    public $calledId;
    public $EcalledType = 'customer';
    public $calledSection;
    public $searchClientText;
    public $clientName;



    public function setCalled($id)
    {
        $this->calledType = $this->EcalledType;
        $this->calledId = $id;
        if ($this->calledType === 'customer') {
            $client = Customer::findOrFail($id);
            $this->clientName = $client->first_name . ' ' . $client->middle_name . ' ' . $client->last_name;
        } elseif ($this->calledType === 'corporate') {
            $client = Corporate::findOrFail($id);
            $this->clientName = $client->name;
        }
        $this->calledSection = false;
        $this->resetPage();
    }

    public function toggleCalled()
    {
        $this->toggle($this->calledSection);
    }

    public function clearCalled()
    {
        $this->reset(['calledType', 'calledId', 'clientName']);
        $this->resetPage();
    }

    public $isMeeting;

    public function toggleIsMeeting()
    {
        $this->toggle($this->isMeeting);
        $this->resetPage();
    }

    public function clearMeeting()
    {
        $this->isMeeting = null;
        $this->resetPage();
    }

    public function toggleSales()
    {
        $this->toggle($this->salesSection);
        if ($this->salesSection) {
            $this->ESalesId = $this->salesId;
        }
    }

    public function setSales()
    {
        $this->salesId = $this->ESalesId;
        $this->toggle($this->salesSection);
        $this->resetPage();
    }

    public function clearSales()
    {
        $this->salesId = null;
        $this->resetPage();
    }

    public function toggleCallTime()
    {
        $this->toggle($this->callTimeSection);
        if ($this->callTimeSection) {
            $this->EcallTime_from = Carbon::parse($this->callTime_from)->toDateString();
            $this->EcallTime_to = Carbon::parse($this->callTime_to)->toDateString();
        }
    }

    public function setCallTime()
    {
        $this->callTime_from = Carbon::parse($this->EcallTime_from);
        $this->callTime_to = Carbon::parse($this->EcallTime_to);
        $this->toggle($this->callTimeSection);
        $this->resetPage();
    }

    public function clearCallTime()
    {
        $this->callTime_from = null;
        $this->callTime_to = null;
        $this->resetPage();
    }

    public function toggleLob()
    {
        $this->toggle($this->lobSection);
        if ($this->lobSection) {
            $this->Eline_of_business = $this->line_of_business;
        }
    }

    public function setLob()
    {
        $this->line_of_business = $this->Eline_of_business;
        $this->toggle($this->lobSection);
        $this->resetPage();
    }

    public function clearLob()
    {
        $this->line_of_business = null;
        $this->resetPage();
    }

    public function exportReport()
    {
        return Followup::exportReport(due_from: $this->callTime_from, due_to: $this->callTime_to, sales_id: $this->salesId, client_type: $this->calledType, client_id: $this->calledId, line_of_business: $this->line_of_business, is_meeting: $this->isMeeting);
    }

    public function render()
    {
        if ($this->salesId) {
            $c = User::find($this->salesId);
            $this->SalesName = ucwords($c->first_name) . ' ' . ucwords($c->last_name);
        }
        $clients = [];
        if ($this->calledSection) {
            if ($this->EcalledType === 'customer') {
                $clients = Customer::userData(searchText: $this->searchClientText)->take(10)->get();
            } elseif ($this->EcalledType === 'corporate') {
                $clients = Corporate::userData(searchText: $this->searchClientText)->take(10)->get();
            }
        }

        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;
        $users = User::all();

        $followups = Followup::report(due_from: $this->callTime_from, due_to: $this->callTime_to, sales_id: $this->salesId, client_type: $this->calledType, client_id: $this->calledId, line_of_business: $this->line_of_business, is_meeting: $this->isMeeting)->paginate(10);
        return view('livewire.followup-report', [
            'followups' => $followups,
            'users' => $users,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS,
            'clients' => $clients,
        ]);
    }
}
