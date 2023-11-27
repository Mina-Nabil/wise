<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Customers\Customer;
use App\models\Customers\Profession;
use App\models\Customers\Address;
use App\models\Customers\Car as CustomerCar;
use App\models\Cars\Car;
use App\models\Cars\CarModel;
use App\models\Cars\Brand;
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



    //add car
    public $addCarSection = false;
    public $carBrand;
    public $models;
    public $carModel;
    public $CarCategory;
    public $cars;
    public $sumInsurance ;
    public $insurancePayment ;
    public $paymentFreqs ;
    
    //add Address
    public $addAddressSection = false;
    public $addressType;
    public $line1;
    public $line2;
    public $flat;
    public $building;
    public $city;
    public $country;

    public function addCar(){

        $this->validate([
            'CarCategory' => 'required|integer|exists:cars,id',
            'sumInsurance' => 'nullable|integer',
            'insurancePayment' => 'nullable|integer',
            'paymentFreqs' => 'nullable|in:' . implode(',', CustomerCar::PAYMENT_FREQS),
        ]);

        $customer = Customer::find($this->customer->id);
        $c = $customer->addCar(
            $this->customer->id,
            $this->CarCategory,
            $this->sumInsurance,
            $this->insurancePayment,
            $this->paymentFreqs
        );
        if($c){
            $this->alert('success' , 'Car added sucessfuly!');
            $this->CarCategory = null;
            $this->sumInsurance = null;
            $this->insurancePayment = null;
            $this->paymentFreqs = null;
            $this->toggleAddCar();
        }else{
            $this->alert('failed','server error');
        }
    }

    public function addAddress(){
        $this->validate([
            'addressType' => 'required|in:' . implode(',', Address::TYPES),
            'line1' => 'required|string|max:255',
            'line2' => 'nullable|string|max:255',
            'flat' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255'
        ]);

        $customer = Customer::find($this->customer->id);
        $a = $customer->addAddress(
            $this->addressType,
            $this->line1,
            $this->line2,
            $this->country,
            $this->city,
            $this->building,
            $this->flat
        );
        if($a){
            $this->alert('success' , 'Address added sucessfuly!');
            $this->addressType = null;
            $this->line1 = null;
            $this->line2 = null;
            $this->country = null;
            $this->city = null;
            $this->building = null;
            $this->flat = null;
            $this->toggleAddAddress();
        }else{
            $this->alert('failed','server error');
        }
    }

    


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


    public function updatedCarBrand($value)
    {
        $this->models = CarModel::where('brand_id', $value)->get();
        if($value === ''){$this->carModel=null;$this->CarCategory=null;}
        $this->CarCategory = null;
    }

    public function updatedCarModel($value)
    {
        $this->cars = Car::where('car_model_id', $value)->get();
    }

    public function toggleAddAddress(){
        $this->toggle($this->addAddressSection);
    }

    public function toggleAddCar()
    {
        $this->toggle($this->addCarSection);
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
        $brands = Brand::all();
        $PAYMENT_FREQS = CustomerCar::PAYMENT_FREQS;
        $addressTypes = Address::TYPES;

        return view('livewire.customer-show',[
            'customer' => $this->customer,
            'GENDERS' => $GENDERS,
            'MARITALSTATUSES' => $MARITALSTATUSES,
            'IDTYPES' => $IDTYPES,
            'professions' => $professions,
            'SALARY_RANGES' => $SALARY_RANGES,
            'INCOME_SOURCES' => $INCOME_SOURCES,
            'brands' => $brands,
            'models' => $this->models,
            'PAYMENT_FREQS' => $PAYMENT_FREQS,
            'addressTypes' => $addressTypes,
        ]);
    }
}
