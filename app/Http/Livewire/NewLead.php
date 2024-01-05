<?php

namespace App\Http\Livewire;

use App\Models\Corporates\Corporate;
use Livewire\Component;
use App\Models\Customers\Customer;
use App\Traits\ToggleSectionLivewire;
use App\Traits\AlertFrontEnd;

class NewLead extends Component
{
    use  AlertFrontEnd, ToggleSectionLivewire;

    public $addLeadSection;
    public $LeadName;
    public $LeadPhone;
    public $LeadEmail;
    public $leadType = 'customer';


    public function toggleAddLead(){
        $this->toggle($this->addLeadSection);
    }

    public function addLead(){


        if ($this->leadType === 'customer') {
            $this->validate([
                'LeadName' => 'required|string|max:255',
                'LeadPhone' => 'required|string|max:255',
                'LeadEmail' => 'nullable|email',
            ]);
    
            $customer = new Customer();
            $res = $customer->newLead(
                $this->LeadName,
                $this->LeadPhone,
                email:$this->LeadEmail,
                owner_id: auth()->id()
            );
        }elseif($this->leadType === 'corporate'){
            $this->validate([
                'LeadName' => 'required|string|max:255',
                'LeadPhone' => 'nullable|string|max:255',
                'LeadEmail' => 'nullable|email',
            ]);

            $corporate = new Corporate();
            $res = $corporate->newLead(
                $this->LeadName,
                email:$this->LeadEmail
            );

            $res->addPhone('home',$this->LeadPhone);

        }
        


        if($res){
            $this->alert('success', 'Lead Added!');
            $this->toggleAddLead();
        }else{
            $this->alert('failed', 'server error');
        }
    }

    public function render()
    {
        return view('livewire.new-lead');
    }
}
