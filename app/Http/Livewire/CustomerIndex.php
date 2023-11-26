<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customers\Customer;
use App\Traits\AlertFrontEnd;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination, AlertFrontEnd;

    public $search;
    public $newLeadName;
    public $newLeadPhone;

    public function addLead(){
        $this->validate([
            'newLeadName' => 'required|string|max:255',
            'newLeadPhone' => 'required|string|max:255'
        ],[],[
            'newLeadName' => 'Lead Name',
            'newLeadPhone' => 'Lead Phone'
        ]);
        $customer = new Customer();
        $res = $customer->newLead($this->newLeadName, $this->newLeadPhone);
        if($res){
            $this->alert('success', 'Lead Added Successfuly!');
            $this->newLeadName = null;
            $this->newLeadPhone = null;
        }else{
            $this->alert('failed', 'Server Error');
        }
    }


    public function render()
    {
        $customers = Customer::paginate(10);

        return view('livewire.customer-index',[
            'customers' => $customers,
        ]);
    }
}
