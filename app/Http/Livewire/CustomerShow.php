<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Customers\Customer;
use App\models\Customers\Profession;
use App\models\Customers\Address;
use App\models\Customers\Car as CustomerCar;
use App\Models\Customers\Relative;
use App\models\Cars\Car;
use App\models\Cars\CarModel;
use App\models\Cars\Brand;
use App\Models\Base\Country;
use App\Models\Customers\Followup;
use App\Models\Customers\Phone;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;

class CustomerShow extends Component
{
    use ToggleSectionLivewire, AlertFrontEnd;
    public $customer;

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

    public $editCustomerSection = false;

    //add car
    public $addCarSection = false;
    public $carBrand;
    public $models;
    public $carModel;
    public $CarCategory;
    public $cars;
    public $sumInsurance;
    public $insurancePayment;
    public $paymentFreqs;
    public $editedCarId = null;

    //add Address
    public $addAddressSection = false;
    public $editedAddressId = null;
    public $addressType;
    public $line1;
    public $line2;
    public $flat;
    public $building;
    public $city;
    public $country;
    public $EditedAddressType;
    public $EditedLine1;
    public $EditedLine2;
    public $EditedFlat;
    public $EditedBuilding;
    public $EditedCity;
    public $EditedCountry;

    //add Relative
    public $addRelativeSection = false;
    public $relativeName;
    public $relation;
    public $relativeGender;
    public $RelativePhone;
    public $relativeBdate;
    public $editedRelativeId;
    public $editedRelativeName;
    public $editedRelation;
    public $editedRelativeGender;
    public $editedRelativePhone;
    public $editedRelativeBdate;

    //Phone
    public $addPhoneSection = false;
    public $editedPhoneId = null;
    public $phoneType;
    public $number;
    public $setPhoneDefault;
    public $editedPhoneType;
    public $editedNumber;

    //followups
    public $addFollowupSection = false;
    public $followupTitle;
    public $followupCallDate;
    public $followupCallTime;
    public $followupDesc;
    public $followupId;
    public $deleteFollowupId;


    public $deletePhoneId;
    public $deleteRelativeId;
    public $deleteAddressId;
    public $deleteCarId;

    public $section = 'profile';

    public function changeSection($section){
        $this->section = $section;
        // dd($this->section);
        $this->mount($this->customer->id);
    }


    public function deleteThisPhone($id)
    {
        $this->deletePhoneId = $id;
    }

    public function deleteThisCar($id)
    {
        $this->deleteCarId = $id;
    }

    public function deleteThisAddress($id)
    {
        $this->deleteAddressId = $id;
    }

    public function deleteAddress()
    {
        $a = Address::find($this->deleteAddressId)->delete();
        if ($a) {
            $this->alert('success', 'Address deleted sucessfuly!');
            $this->deleteAddressId = null;
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function closeEditAddress()
    {
        $this->editedAddressId = null;
    }

    public function closeEditFollowup()
    {
        $this->followupId = null;
        $this->followupTitle = null;
        $this->followupCallDate = null;
        $this->followupCallTime = null;
        $this->followupDesc = null;
    }

    public function closeFollowupSection()
    {
        $this->followupTitle = null;
        $this->followupCallDate = null;
        $this->followupCallTime = null;
        $this->followupDesc = null;
        $this->addFollowupSection = false;
    }

    public function OpenAddFollowupSection()
    {
        $this->addFollowupSection = true;
    }

    public function editThisFollowup($id){
        $this->followupId = $id;
        $f = Followup::find($id);
        $this->followupTitle = $f->title;
        $combinedDateTime = new \DateTime($f->call_time);
        $this->followupCallDate = $combinedDateTime->format('Y-m-d');
        $this->followupCallTime = $combinedDateTime->format('H:i:s');
        $this->followupDesc = $f->desc;
    }

    public function deleteThisFollowup($id){
        $this->deleteFollowupId = $id;
    }

    public function dismissDeleteFollowup(){
        $this->deleteFollowupId = null;
    }

    public function deleteFollowup(){
        $res = Followup::find($this->deleteFollowupId)->delete();
        if ($res) {
            $this->alert('success', 'Followup Deleted successfuly');
            $this->dismissDeleteFollowup();
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addFollowup()
    {
        $this->validate([
            'followupTitle' => 'required|string|max:255',
            'followupCallDate' => 'nullable|date',
            'followupCallTime' => 'nullable',
            'followupDesc' => 'nullable|string|max:255'
        ]);

        $combinedDateTimeString = $this->followupCallDate . ' ' . $this->followupCallTime;
        $combinedDateTime = new \DateTime($combinedDateTimeString);

        $customer = Customer::find($this->customer->id);

        $res = $customer->addFollowup(
            $this->followupTitle,
            $combinedDateTime,
            $this->followupDesc
        );

        if ($res) {
            $this->alert('success', 'Followup added successfuly');
            $this->closeFollowupSection();
            $this->mount($this->customer->id);
            return redirect()->route('customers.show' , $this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editFollowup()
    {
        $this->validate([
            'followupTitle' => 'required|string|max:255',
            'followupCallDate' => 'nullable|date',
            'followupCallTime' => 'nullable',
            'followupDesc' => 'nullable|string|max:255'
        ]);

        $combinedDateTimeString = $this->followupCallDate . ' ' . $this->followupCallTime;
        $combinedDateTime = new \DateTime($combinedDateTimeString);

        $followup = Followup::find($this->followupId);

        $res = $followup->editInfo(
            $this->followupTitle,
            $combinedDateTime,
            $this->followupDesc
        );

        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->closeEditFollowup();
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setFollowupAsCalled($id)
    {
        $res = Followup::find($id)->setAsCalled();
        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setFollowupAsCancelled($id)
    {
        $res = Followup::find($id)->setAsCancelled();
        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editAddress()
    {
        $this->validate([
            'EditedAddressType' => 'required|in:' . implode(',', Address::TYPES),
            'EditedLine1' => 'required|string|max:255',
            'EditedLine2' => 'nullable|string|max:255',
            'EditedFlat' => 'nullable|string|max:255',
            'EditedBuilding' => 'nullable|string|max:255',
            'EditedCity' => 'nullable|string|max:255',
            'EditedCountry' => 'nullable|string|max:255'
        ]);

        $address = Address::find($this->editedAddressId);
        $a = $address->editInfo(
            $this->EditedAddressType,
            $this->EditedLine1,
            $this->EditedLine2,
            $this->EditedCountry,
            $this->EditedCity,
            $this->EditedBuilding,
            $this->EditedFlat
        );
        if ($a) {
            $this->alert('success', 'Address edited successfuly');
            $this->editedAddressId = null;
            $this->EditedAddressType = null;
            $this->EditedLine1 = null;
            $this->EditedLine2 = null;
            $this->EditedFlat = null;
            $this->EditedBuilding = null;
            $this->EditedCity = null;
            $this->EditedCountry = null;
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editThisAddress($id)
    {
        $this->editedAddressId = $id;
        $a = Address::find($this->editedAddressId);
        $this->EditedAddressType = $a->type;
        $this->EditedLine1 = $a->line_1;
        $this->EditedLine2 = $a->line_2;
        $this->EditedFlat = $a->flat;
        $this->EditedBuilding = $a->building;
        $this->EditedCity = $a->city;
        $this->EditedCountry = $a->country;
    }

    public function dismissDeleteAddress()
    {
        $this->deleteAddressId = null;
    }

    public function editThisPhone($id)
    {
        $this->editedPhoneId = $id;
        $p = Phone::find($this->editedPhoneId);
        $this->editedPhoneType = $p->type;
        $this->editedNumber = $p->number;
    }

    public function closeEditPhone()
    {
        $this->editedPhoneId = null;
    }

    public function editPhone()
    {
        $this->validate([
            'editedPhoneType' => 'required|in:' . implode(',', Phone::TYPES),
            'editedNumber' => 'required|string|max:255',
        ]);
        $p = Phone::find($this->editedPhoneId);
        $res = $p->editInfo($this->editedPhoneType, $this->editedNumber);
        if ($res) {
            $this->alert('success', 'Phone Edited sucessfuly!');
            $this->editedPhoneId = null;
            $this->editedPhoneType = null;
            $this->editedNumber = null;
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deletePhone()
    {
        $p = Phone::find($this->deletePhoneId)->delete();
        if ($p) {
            $this->alert('success', 'Phone deleted sucessfuly!');
            $this->deletePhoneId = null;
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deleteCar()
    {
        $c = CustomerCar::find($this->deleteCarId)->delete();
        if ($c) {
            $this->alert('success', 'Car deleted sucessfuly!');
            $this->deleteCarId = null;
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editThisCar($id)
    {
        $this->editedCarId = $id;
        $c = CustomerCar::find($id);
        $this->carBrand = $c->car->car_model->brand->id;
        $this->carModel =  $c->car->car_model->id;
        $this->CarCategory =  $c->car->id;
        $this->sumInsurance  =  $c->sum_insured;
        $this->insurancePayment  =  $c->insurance_payment;
        $this->paymentFreqs =  $c->payment_frequency;
        $this->models = CarModel::where('brand_id', $this->carBrand)->get();
        $this->cars = Car::where('car_model_id', $this->carModel)->get();
    }

    public function updateCar()
    {
        $this->validate([
            'CarCategory' => 'required|integer|exists:cars,id',
            'sumInsurance' => 'nullable|integer',
            'insurancePayment' => 'nullable|integer',
            'paymentFreqs' => 'nullable|in:' . implode(',', CustomerCar::PAYMENT_FREQS),
        ]);
        $c = CustomerCar::find($this->editedCarId);
        $c->editInfo(
            $this->CarCategory,
            null,
            $this->sumInsurance,
            $this->insurancePayment,
            $this->paymentFreqs
        );
        if ($c) {
            $this->alert('success', 'Car Edited sucessfuly!');
            $this->editedCarId = null;
            $this->carBrand = null;
            $this->carModel =  null;
            $this->CarCategory =  null;
            $this->sumInsurance  =  null;
            $this->insurancePayment  = null;
            $this->paymentFreqs =  null;
            $this->models = null;
            $this->cars = null;
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function closeEditCar()
    {
        $this->editedCarId = null;
        $this->carBrand = null;
        $this->carModel =  null;
        $this->CarCategory =  null;
        $this->sumInsurance  =  null;
        $this->insurancePayment  = null;
        $this->paymentFreqs =  null;
        $this->models = null;
        $this->cars = null;
    }

    public function dismissDeletePhone()
    {
        $this->deletePhoneId = null;
    }

    public function dismissDeleteCar()
    {
        $this->deleteCarId = null;
    }

    public function addPhone()
    {
        $this->validate([
            'phoneType' => 'required|in:' . implode(',', Phone::TYPES),
            'number' => 'required|string|max:255',
        ]);
        $customer = Customer::find($this->customer->id);
        $c = $customer->addPhone(
            $this->phoneType,
            $this->number,
            $this->setPhoneDefault
        );
        if ($c) {
            $this->alert('success', 'Phone added sucessfuly!');
            $this->phoneType = null;
            $this->number = null;
            $this->setPhoneDefault = null;
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editThisRelative($id)
    {
        $this->editedRelativeId = $id;

        $r = Relative::find($this->editedRelativeId);
        $this->editedRelativeName = $r->name;
        $this->editedRelation = $r->relation;
        $this->editedRelativeGender = $r->gender;
        $this->editedRelativePhone = $r->phone;
        $this->editedRelativeBdate = ($r->birth_date ? $r->birth_date->toDateString() : null);
    }

    public function editRelative()
    {
        $this->validate([
            'editedRelativeName' => 'required|string|max:255',
            'editedRelation' => 'required|in:' . implode(',', Relative::RELATIONS),
            'editedRelativeGender' => 'nullable|in:' . implode(',', Customer::GENDERS),
            'editedRelativePhone' => 'nullable|string|max:255',
            'editedRelativeBdate' => 'nullable|date'
        ]);

        $r = Relative::find($this->editedRelativeId);
        $res = $r->editInfo(
            $this->editedRelativeName,
            $this->editedRelation,
            $this->editedRelativeGender,
            $this->editedRelativePhone,
            $this->editedRelativeBdate
        );
        if ($res) {
            $this->alert('success', 'Relative Edited sucessfuly!');
            $this->editedRelativeId = null;
            $this->relativeName = null;
            $this->relation = null;
            $this->relativeGender = null;
            $this->RelativePhone = null;
            $this->relativeBdate = null;
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function closeEditRelative()
    {
        $this->editedRelativeId = null;
    }

    public function deleteThisRelative($id)
    {
        $this->deleteRelativeId = $id;
    }

    public function dismissDeleteRelative()
    {
        $this->deleteRelativeId = null;
    }

    public function deleteRelative()
    {
        $r = Relative::find($this->deleteRelativeId)->delete();
        if ($r) {
            $this->alert('success', 'Relative deleted sucessfuly!');
            $this->deleteRelativeId = null;
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addRelative()
    {
        $this->validate([
            'relativeName' => 'required|string|max:255',
            'relation' => 'required|in:' . implode(',', Relative::RELATIONS),
            'relativeGender' => 'nullable|in:' . implode(',', Customer::GENDERS),
            'RelativePhone' => 'nullable|string|max:255',
            'relativeBdate' => 'nullable|date'
        ]);
        $customer = Customer::find($this->customer->id);
        $r = $customer->addRelative(
            $this->relativeName,
            $this->relation,
            $this->relativeGender,
            $this->RelativePhone,
            $this->relativeBdate
        );
        if ($r) {
            $this->alert('success', 'Relative added sucessfuly!');
            $this->relativeName = null;
            $this->relation = null;
            $this->relativeGender = null;
            $this->RelativePhone = null;
            $this->relativeBdate = null;
            $this->toggleAddRelative();
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addCar()
    {

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
        if ($c) {
            $this->alert('success', 'Car added sucessfuly!');
            $this->CarCategory = null;
            $this->sumInsurance = null;
            $this->insurancePayment = null;
            $this->paymentFreqs = null;
            $this->toggleAddCar();
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addAddress()
    {
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
        if ($a) {
            $this->alert('success', 'Address added sucessfuly!');
            $this->addressType = null;
            $this->line1 = null;
            $this->line2 = null;
            $this->country = null;
            $this->city = null;
            $this->building = null;
            $this->flat = null;
            $this->toggleAddAddress();
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setPhoneAsDefault($id)
    {
        $p = Phone::find($id)->setAsDefault();
        if ($p) {
            $this->alert('success', 'Phone set as primary!');
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editInfo()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'arabic_name' => 'nullable|string|max:255',
            'bdate' => 'nullable|date',
            'email' =>  'nullable|email',
            'gender' =>  'nullable|in:' . implode(',', Customer::GENDERS),
            'maritalStatus' =>  'nullable|in:' . implode(',', Customer::MARITALSTATUSES),
            'idType' =>  'nullable|in:' . implode(',', Customer::IDTYPES),
            'idNumber' => 'nullable|string|max:255',
            'nationalId' => 'nullable|integer|exists:countries,id',
            'profession_id' => 'nullable|exists:professions,id',
            'salaryRange' => 'nullable|in:' . implode(',', Customer::SALARY_RANGES),
            'incomeSource' =>  'nullable|in:' . implode(',', Customer::INCOME_SOURCES),
        ]);
        $customer = Customer::find($this->customer->id);
        $c = $customer->editCustomer(
            $this->name,
            $this->arabic_name,
            $this->bdate,
            $this->email,
            $this->gender,
            $this->maritalStatus,
            $this->idType,
            $this->idNumber,
            $this->nationalId,
            $this->profession_id,
            $this->salaryRange,
            $this->incomeSource
        );
        if ($c) {
            $this->alert('success', 'Updated Successfuly!');
            $this->mount($this->customer->id);
            $this->toggleEditCustomer();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function mount($customerId)
    {
        $this->customer = Customer::findOrFail($customerId);

        $this->name = $this->customer->name;
        $this->arabic_name = $this->customer->arabic_name;
        $this->email = $this->customer->email;
        $this->bdate = ($this->customer->birth_date ? $this->customer->birth_date->toDateString() : null);
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
        if ($value === '') {
            $this->carModel = null;
            $this->CarCategory = null;
        }
        $this->CarCategory = null;
    }

    public function updatedCarModel($value)
    {
        $this->cars = Car::where('car_model_id', $value)->get();
    }

    public function toggleAddAddress()
    {
        $this->toggle($this->addAddressSection);
    }

    public function toggleAddCar()
    {
        $this->toggle($this->addCarSection);
    }

    public function toggleAddPhone()
    {
        $this->toggle($this->addPhoneSection);
    }

    public function toggleAddRelative()
    {
        $this->toggle($this->addRelativeSection);
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
        $RELATIONS = Relative::RELATIONS;
        $countries = Country::all();
        $phoneTypes = Phone::TYPES;

        return view('livewire.customer-show', [
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
            'RELATIONS' => $RELATIONS,
            'countries' => $countries,
            'phoneTypes' => $phoneTypes,
        ]);
    }
}
