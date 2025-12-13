<?php

namespace App\Http\Livewire;

use App\Models\Corporates\Corporate;
use App\Models\Marketing\Campaign;
use Livewire\Component;
use App\Models\Customers\Customer;
use App\Traits\ToggleSectionLivewire;
use App\Traits\AlertFrontEnd;

class NewLead extends Component
{
    use  AlertFrontEnd, ToggleSectionLivewire;

    public $addLeadSection;
    public $leadFirstName;
    public $leadMiddleName;
    public $leadLastName;
    public $leadArabicFirstName;
    public $leadArabicMiddleName;
    public $leadArabicLastName;
    public $corporateName;
    public $LeadPhone;
    public $LeadEmail;
    public $note;
    public $followupCallDateTime;
    public $leadType = 'customer';
    public $followup_is_meeting = false;
    public $campaignId;
    public $channel;


    public function toggleAddLead()
    {
        $this->toggle($this->addLeadSection);
    }

    public function addLead()
    {
        if($this->followupCallDateTime === ''){$this->followupCallDateTime = null ;}

        if ($this->leadType === 'customer') {
            $this->validate([
                'leadFirstName' => 'required|string|max:255',
                'leadMiddleName' => 'nullable|string|max:255',
                'leadLastName' => 'required|string|max:255',
                'leadArabicFirstName' => 'nullable|string|max:255',
                'leadArabicMiddleName' => 'nullable|string|max:255',
                'leadArabicLastName' => 'nullable|string|max:255',
                'LeadPhone' => 'required|string|max:255',
                'LeadEmail' => 'nullable|email',
                'note' => 'nullable|string|max:255',
                'followupCallDateTime' => 'nullable|date_format:Y-m-d\TH:i',
                'channel' => 'nullable|string|max:255',
            ]);

            if ($this->followupCallDateTime || $this->followup_is_meeting){
                $this->validate([
                    'followupCallDateTime' => 'required',
                    'followup_is_meeting' => 'boolean',
                ]);
            }

            $customer = new Customer();
            $res = $customer->newLead(
                $this->leadFirstName,
                $this->leadLastName,
                $this->LeadPhone,
                $this->leadMiddleName,
                $this->leadArabicFirstName,
                $this->leadArabicMiddleName,
                $this->leadArabicLastName,
                email: $this->LeadEmail,
                owner_id: auth()->id(),
                note: $this->note,
                campaign_id: $this->campaignId,
                channel: $this->channel
            );

            if ($this->followupCallDateTime || $this->followup_is_meeting) {
                $fres = $res->addFollowup('Initial Contact',new \DateTime($this->followupCallDateTime),$this->note,$this->followup_is_meeting);
            }else{
                $fres = true;
            }
        } elseif ($this->leadType === 'corporate') {
            $this->validate([
                'corporateName' => 'required|string|max:255',
                'LeadPhone' => 'nullable|string|max:255',
                'LeadEmail' => 'nullable|email',
                'note' => 'nullable|string|max:255',
                'followupCallDateTime' => 'nullable|date_format:Y-m-d\TH:i',
                'channel' => 'nullable|string|max:255',
            ]);

            $corporate = new Corporate();
            $res = $corporate->newLead(
                $this->corporateName,
                email: $this->LeadEmail,
                note: $this->note,
                campaign_id: $this->campaignId,
                channel: $this->channel
            );

            $res->addPhone('home', $this->LeadPhone);

            if ($this->followupCallDateTime) {
                $fres = $res->addFollowup('Initial Contact',new \DateTime($this->followupCallDateTime),$this->note);
            }else{
                $fres = true;
            }
        }



        if ($res && $fres) {
            $this->alert('success', 'Lead Added!');
            // dd($res->id);
            $this->emit('dataReceived', ['clientTypeRecieved' => $this->leadType , 'clientIdRecieved' => $res->id]);
            $this->toggleAddLead();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function render()
    {
        $campaigns = Campaign::all();
        return view('livewire.new-lead', [
            'campaigns' => $campaigns,
        ]);
    }
}
