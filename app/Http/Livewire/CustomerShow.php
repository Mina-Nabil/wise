<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Customers\Customer;
use App\models\Customers\Profession;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;

class CustomerShow extends Component
{
    use ToggleSectionLivewire,AlertFrontEnd;
    public $customer;

    public $name;
    public $arabic_name;
    public $email;
    public $phone1;
    public $phone2;
    public $bdate;
    public $gender;
    public $maritalStatus;
    public $idType;
    public $idNumber;
    public $nationalId;
    public $profession_id;
    public $salaryRange;
    public $incomeSource;
    
    public $editCustomerSection = false;

    public function mount($customerId){
        $this->customer = Customer::findOrFail($customerId);

        $this->name = $this->customer->name;
        $this->arabic_name = $this->customer->arabic_name;
        $this->email = $this->customer->email;
        $this->phone1 = $this->customer->phone;
        $this->phone2 = $this->customer->phone_2;
        $this->bdate = $this->customer->birth_date->format('d/m/Y');
        $this->gender = $this->customer->gender;
        $this->maritalStatus = $this->customer->marital_status;
        $this->idType = $this->customer->id_type;
        $this->idNumber = $this->customer->id_number;
        $this->nationalId = $this->customer->nationality_id;
        $this->profession_id = $this->customer->profession_id;
        $this->salaryRange  = $this->customer->salary_range;
        $this->incomeSource = $this->customer->income_source;

    }

    public function toggleEditCustomer()
    {
        $this->toggle($this->editCustomerSection);
    }

    public function render()
    {
        $GENDERS = Customer::GENDERS;
        $MARITALSTATUSES = Customer::MARITALSTATUSES;
        $IDTYPES = Customer::IDTYPES;
        $SALARY_RANGES = Customer::SALARY_RANGES;
        $INCOME_SOURCES = Customer::INCOME_SOURCES;
        $professions = Profession::all();
        return view('livewire.customer-show',[
            'customer' => $this->customer,
            'GENDERS' => $GENDERS,
            'MARITALSTATUSES' => $MARITALSTATUSES,
            'IDTYPES' => $IDTYPES,
            'professions' => $professions,
            'SALARY_RANGES' => $SALARY_RANGES,
            'INCOME_SOURCES' => $INCOME_SOURCES,
        ]);
    }
}
