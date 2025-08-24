<?php

namespace App\Http\Livewire;

use App\Models\Corporates\Corporate;
use App\Models\Customers\Customer;
use App\Models\Insurance\Policy;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Livewire\Component;

class NewMeeting extends Component
{
    use  AlertFrontEnd, ToggleSectionLivewire;

    public $followupCallDateTime;
    public $meetingType = 'Customer';
    public $followup_is_meeting = false;
    public $addMeetingSection;

    public $searchClient;
    public $clientNames;
    public $selectedClientId;
    public $selectedClientName;
    public $owner;
    public $followupTitle;
    public $followupDesc;
    public $FollowupLineOfBussiness = Policy::BUSINESS_PERSONAL_MOTOR;

    public function resetClient(){
        $this->reset(['clientNames','selectedClientId','selectedClientName', 'owner']);
    }

    public function selectClient($id)
    {
        if ($this->meetingType === 'Customer') {
            $res = Customer::find($id);


            $this->selectedClientName = $res->first_name . ' ' . $res->middle_name . ' ' . $res->last_name;
        } elseif ($this->meetingType === 'Corporate') {
            $res = Corporate::find($id);
            $this->selectedClientName = $res->name;
        }

        $this->owner = $res;
        $this->clientNames = null;
        $this->searchClient = null;
    }

    public function updatedMeetingType()
    {
        $this->clientNames = null;
        $this->searchClient = null;
    }

    public function updatedSearchClient()
    {
        if ($this->meetingType == 'Customer' && !$this->searchClient == '') {
            $this->clientNames = Customer::userData($this->searchClient)
                ->get()
                ->take(5);
        } elseif ($this->meetingType == 'Corporate' && !$this->searchClient == '') {
            $this->clientNames = Corporate::userData($this->searchClient)
                ->get()
                ->take(5);
        }

        // dd($this->clientNames);
    }



    public function toggleAddMeeting()
    {
        $this->toggle($this->addMeetingSection);
    }

    public function addMeeting()
    {

        if ($this->meetingType === 'Customer') {
            $res = Customer::findOrFail($this->owner->id);

            $this->validate([
                'followupCallDateTime' => 'required|date_format:Y-m-d\TH:i',
            ]);

            if ($this->followupCallDateTime || $this->followup_is_meeting) {
                $fres = $res->addFollowup($this->followupTitle,$this->followupCallDateTime,$this->followupDesc,true,$this->FollowupLineOfBussiness);
            }else{
                $fres = true;
            }
        } elseif ($this->meetingType === 'Corporate') {

            $res = Corporate::findOrFail($this->owner->id);

            $this->validate([
                'followupCallDateTime' => 'required|date_format:Y-m-d\TH:i',
            ]);

            if ($this->followupCallDateTime) {
                $fres = $res->addFollowup($this->followupTitle,$this->followupCallDateTime,$this->followupDesc,true,$this->FollowupLineOfBussiness);
            }else{
                $fres = true;
            }
        }

        if ($res && $fres) {
            $this->alert('success', 'Meeting Added!');
            // dd($res->id);
            $this->emit('dataReceived', ['clientTypeRecieved' => $this->meetingType , 'clientIdRecieved' => $res->id]);
            $this->toggleAddMeeting();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function render()
    {
        $LINES_OF_BUSINESS = Policy::PERSONAL_TYPES;
        return view('livewire.new-meeting',[
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS
        ]);
    }
}
