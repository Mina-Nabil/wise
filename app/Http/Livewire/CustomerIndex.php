<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customers\Customer;
use App\Models\Customers\Profession;
use App\Models\Base\Country;
use App\Traits\AlertFrontEnd;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;


class CustomerIndex extends Component
{
    use WithPagination, AlertFrontEnd, ToggleSectionLivewire;

    public $search;

    public $addCustomerSection = false;
    public $ownerId;
    public $name;
    public $arabic_name;
    public $email;
    public $bdate;
    public $gender;
    public $maritalStatus;
    public $idType;
    public $idNumber;
    public $nationalId;
    public $profession_id;
    public $salaryRange;
    public $incomeSource;

    public $addLeadSection;
    public $LeadName;
    public $LeadPhone;

    public function addLead(){
        $this->validate([
            'LeadName' => 'required|string|max:255',
            'LeadPhone' => 'required|string|max:255',
        ]);

        $customer = new Customer();
        $res = $customer->newLead(
            $this->LeadName,
            $this->LeadPhone,
            null, null, null, null, null,null, null, null, null, null, null,
            auth()->id()
        );
        if($res){
            $this->alert('success', 'Lead Added!');
            $this->toggleAddLead();
        }else{
            $this->alert('failed', 'server error');
        }
    }

    public function addCustomer(){
        $this->validate([
            'name' => 'required|string|max:255',
            'arabic_name' => 'nullable|string|max:255',
            'bdate' => 'nullable|date',
            'email' =>  'required|email',
            'gender' =>  'required|in:' . implode(',', Customer::GENDERS),
            'maritalStatus' =>  'nullable|in:' . implode(',', Customer::MARITALSTATUSES),
            'idType' =>  'nullable|in:' . implode(',', Customer::IDTYPES),
            'idNumber' => 'nullable|string|max:255',
            'nationalId' => 'nullable|integer|exists:countries,id',
            'profession_id' => 'nullable|exists:professions,id',
            'salaryRange' => 'nullable|in:' . implode(',', Customer::SALARY_RANGES),
            'incomeSource' =>  'nullable|in:' . implode(',', Customer::INCOME_SOURCES),
        ]);

        $customer = new Customer();
        $res = $customer->newCustomer(
            auth()->id(),
            $this->name,
            $this->gender,
            $this->email,
            $this->arabic_name,
            $this->bdate,
            $this->maritalStatus,
            $this->idType,
            $this->idNumber,
            $this->nationalId,
            $this->profession_id,
            $this->salaryRange,
            $this->incomeSource
        );
        if($res){
            redirect()->route('customers.show',$res->id);
        }else{
            $this->alert('failed', 'server error');
        }
    }

    public function toggleAddLead(){
        $this->toggle($this->addLeadSection);
    }

    public function toggleAddCustomer(){
        $this->toggle($this->addCustomerSection);
    }

    public function redirectToShowPage($id)
    {
        return redirect(route('customers.show',  $id));
    }

    public function render()
    {
        
        $GENDERS = Customer::GENDERS;
        $MARITALSTATUSES = Customer::MARITALSTATUSES;
        $IDTYPES = Customer::IDTYPES;
        $SALARY_RANGES = Customer::SALARY_RANGES;
        $INCOME_SOURCES = Customer::INCOME_SOURCES;
        $professions = Profession::all();
        $countries = Country::all();
        $customers = Customer::paginate(10);

        return view('livewire.customer-index',[
            'customers' => $customers,
            'GENDERS' => $GENDERS,
            'MARITALSTATUSES' => $MARITALSTATUSES,
            'IDTYPES' => $IDTYPES,
            'professions' => $professions,
            'SALARY_RANGES' => $SALARY_RANGES,
            'INCOME_SOURCES' => $INCOME_SOURCES,
            'countries' => $countries,
        ]);
    }
}
