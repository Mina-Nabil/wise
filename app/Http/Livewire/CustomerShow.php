<?php

namespace App\Http\Livewire;

use App\Models\Base\Area;
use App\Models\Base\City;
use Livewire\Component;
use App\Models\Customers\Customer;
use App\Models\Customers\Profession;
use App\Models\Customers\BankAccount;
use App\Models\Customers\Address;
use App\Models\Customers\Car as CustomerCar;
use App\Models\Customers\Relative;
use App\Models\Insurance\Policy;
use App\Models\Cars\Car;
use Carbon\Carbon;
use App\models\Insurance\Company;
use App\models\Cars\CarModel;
use App\models\Cars\Brand;
use App\Models\Base\Country;
use App\Models\Cars\CarPrice;
use App\Models\Customers\Followup;
use App\Models\Customers\Interest;
use App\Models\Customers\Phone;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CustomerShow extends Component
{
    use ToggleSectionLivewire, AlertFrontEnd, WithFileUploads;

    public $customer;

    public $firstName;
    public $middleName;
    public $lastName;
    public $ArabicFirstName;
    public $ArabicMiddleName;
    public $ArabicLastName;

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
    public $idDoc;
    public $driverLicenseDoc;
    public $customerNote;
    public $customerNoteSec = false;

    public $editCustomerSection = false;

    //add car
    public $addCarSection = false;
    public $carBrand;
    public $models;
    public $carModel;
    public $CarCategory;
    public $modelYears;
    public $modelYear;
    public $cars;
    public $sumInsurance;
    public $insurancePayment;
    public $paymentFreqs;
    public $editedCarId = null;
    public $renewalDate;
    public $wiseInsured = false;
    public $insuranceCompanyId;

    //add Address
    public $addAddressSection = false;
    public $editedAddressId = null;
    public $addressType;
    public $line1;
    public $line2;
    public $flat;
    public $building;
    public $city;
    public $area;
    public $country;
    public $EditedAddressType;
    public $EditedLine1;
    public $EditedLine2;
    public $EditedFlat;
    public $EditedBuilding;
    public $EditedCity;
    public $EditedArea;
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
    public $callerNoteSec = false;

    public $callerNotetype;
    public $callerNoteId;
    public $note;

    //bank account
    public $accountType;
    public $bankName;
    public $accountNumber;
    public $ownerName;
    public $evidenceDoc;
    public $iban;
    public $bankBranch;
    public $bankAccountId;
    public $deleteBankAccountId;
    public $addBankAccountSection;

    //interests
    public $interestSection = false;
    public $editedLob;
    public $interested;
    public $interestNote;
    public $editInteresetSec = false;

    //customer ralative
    public $addCustomerRelativeSection;
    public $searchCustomer; // value for searching customers
    public $customerResult;
    public $selectedRelative;
    public $custRelation;
    public $deleteRelativeCustId;




    public $section = 'profile';

    protected $queryString = ['section'];

    public function changeSection($section)
    {
        $this->section = $section;
        // dd($this->section);
        $this->mount($this->customer->id);
    }

    public function deleteThisRelativeCustomer($id)
    {
        $this->deleteRelativeCustId = $id;
    }

    public function dismissDeleteRelativeCustomer()
    {
        $this->deleteRelativeCustId = null;
    }

    public function deleteRelativeCustomer()
    {
        $this->customer->customer_relatives()->detach($this->deleteRelativeCustId);
        $this->dismissDeleteRelativeCustomer();
        $this->mount($this->customer->id);
    }

    public function addRelariveCustomer()
    {
        $this->validate([
            'custRelation' => 'required|in:' . implode(',', Relative::RELATIONS),
        ]);


        $res = $this->customer->addCustomerRelative($this->selectedRelative->id, $this->custRelation);

        if ($res) {
            $this->alert('success', 'Customer relative added');
            $this->selectedRelative = null;
            $this->custRelation = null;
            $this->toggleAddCustomerRelative();
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function openEditNote()
    {
        $this->customerNoteSec = true;
        $this->customerNote = $this->customer->note;
    }

    public function closeEditNote()
    {
        $this->customerNoteSec = false;
        $this->customerNote = null;
    }

    public function setNote()
    {
        if ($this->customerNote === '') {
            $this->customerNote = null;
        }
        $res = $this->customer->setCustomerNote($this->customerNote);
        if ($res) {
            $this->closeEditNote();
            $this->mount($this->customer->id);
            $this->alert('success', 'Note updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }


    public function clearCustomerRelative()
    {
        $this->selectedRelative = null;
    }

    public function selectCustomerRelative($id)
    {
        $this->selectedRelative = Customer::find($id);

        $this->customerResult = null;
        $this->searchCustomer = null;
    }

    public function updatedSearchCustomer()
    {
        $this->customerResult = Customer::userData($this->searchCustomer)->get()->take(5);
    }

    public function toggleAddCustomerRelative()
    {
        $this->toggle($this->addCustomerRelativeSection);
    }

    public function toggleAddInterest()
    {
        $this->toggle($this->interestSection);
    }

    public function toggleAddBankAccount()
    {
        $this->toggle($this->addBankAccountSection);
    }

    public function editThisBankAccount($id)
    {
        $this->bankAccountId = $id;
        $b = BankAccount::find($id);
        $this->accountType = $b->type;
        $this->bankName = $b->bank_name;
        $this->accountNumber = $b->account_number;
        $this->ownerName = $b->owner_name;
        $this->evidenceDoc = $b->evidence_doc;
        $this->iban = $b->iban;
        $this->bankBranch = $b->bank_branch;
    }

    public function closeEditBankAccount()
    {
        $this->bankAccountId = null;
        $this->accountType = null;
        $this->bankName = null;
        $this->accountNumber = null;
        $this->ownerName = null;
        $this->evidenceDoc = null;
        $this->iban = null;
        $this->bankBranch = null;
    }

    public function deleteThisBankAccount($id)
    {
        $this->deleteBankAccountId = $id;
    }

    public function closeDeleteBankAccount()
    {
        $this->deleteBankAccountId = null;
    }


    public function addBankAccount()
    {
        $this->validate([
            'accountType' =>  'required|in:' . implode(',', BankAccount::TYPES),
            'bankName' => 'required|string|max:255',
            'accountNumber' =>  'required|string|max:255',
            'ownerName' =>  'required|string|max:255',
            'evidenceDoc' =>  'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
            'iban' => 'nullable|string|max:255',
            'bankBranch' => 'nullable|string|max:255',
        ]);
        $c = Customer::find($this->customer->id);

        if ($this->evidenceDoc) {
            $evidenceDoc_url = $this->evidenceDoc->store(Customer::FILES_DIRECTORY, 's3');
        } else {
            $evidenceDoc_url = null;
        }


        $res = $c->addBankAccount(
            $this->accountType,
            $this->bankName,
            $this->accountNumber,
            $this->ownerName,
            $evidenceDoc_url,
            $this->iban,
            $this->bankBranch,
            false
        );
        if ($res) {
            $this->alert('success', 'Account Added successfuly');
            $this->accountType = null;
            $this->bankName = null;
            $this->accountNumber = null;
            $this->ownerName = null;
            $this->evidenceDoc = null;
            $this->iban = null;
            $this->bankBranch = null;
            $this->toggleAddBankAccount();
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editBankAccount()
    {
        $a = BankAccount::find($this->bankAccountId);

        if (is_null($a->evidence_doc) && (is_null($this->evidenceDoc))) {
            $evidenceDoc_url = null;
        } elseif (!is_null($a->evidence_doc) && (is_null($this->evidenceDoc))) {
            $evidenceDoc_url = null;
        } elseif (!is_null($a->evidence_doc) && (!is_null($this->evidenceDoc))) {
            if (is_string($this->evidenceDoc)) {
                $this->evidenceDoc = null;
                $evidenceDoc_url = $a->evidence_doc;
            }
        }

        $this->validate([
            'accountType' =>  'required|in:' . implode(',', BankAccount::TYPES),
            'bankName' => 'required|string|max:255',
            'accountNumber' =>  'required|string|max:255',
            'ownerName' =>  'required|string|max:255',
            'evidenceDoc' =>   'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
            'iban' => 'nullable|string|max:255',
            'bankBranch' => 'nullable|string|max:255',
        ]);
        $b = BankAccount::find($this->bankAccountId);

        if (!is_string($this->evidenceDoc) && !is_null($this->evidenceDoc)) {
            $evidenceDoc_url = $this->evidenceDoc->store(Customer::FILES_DIRECTORY, 's3');
        }

        $res = $b->editInfo(
            $this->accountType,
            $this->bankName,
            $this->accountNumber,
            $this->ownerName,
            $evidenceDoc_url,
            $this->iban,
            $this->bankBranch,
            false
        );
        if ($res) {
            $this->alert('success', 'Account edited successfuly');
            $this->closeEditBankAccount();
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deleteBankAccount()
    {
        $r = BankAccount::find($this->deleteBankAccountId)->delete();
        if ($r) {
            $this->alert('success', 'Account deleted sucessfuly!');
            $this->deleteBankAccountId = null;
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function clearEvidenceDoc()
    {
        $this->evidenceDoc = null;
    }


    public function downloadDoc($url)
    {
        $filename = $this->customer->name . '_document.' . pathinfo($url, PATHINFO_EXTENSION);
        $fileContents = Storage::disk('s3')->get($url);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function downloadEvidenceDoc($url)
    {

        $filename = $this->customer->name . '_evidence_document.' . pathinfo($url, PATHINFO_EXTENSION);
        $fileContents = Storage::disk('s3')->get($url);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function clearIdDoc()
    {
        $this->idDoc = null;
    }

    public function cleardriverLicenseDoc()
    {
        $this->driverLicenseDoc = null;
    }


    public function toggleCallerNote($type = null, $id = null)
    {

        $this->callerNotetype = $type;
        $this->callerNoteId = $id;
        $this->toggle($this->callerNoteSec);
    }

    public function submitCallerNote()
    {

        if ($this->callerNotetype === 'called') {
            $this->setFollowupAsCalled($this->callerNoteId, $this->note);
        } elseif ($this->callerNotetype === 'cancelled') {
            $this->setFollowupAsCancelled($this->callerNoteId, $this->note);
        }
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

    public function editThisFollowup($id)
    {
        $this->followupId = $id;
        $f = Followup::find($id);
        $this->followupTitle = $f->title;
        $combinedDateTime = new \DateTime($f->call_time);
        $this->followupCallDate = $combinedDateTime->format('Y-m-d');
        $this->followupCallTime = $combinedDateTime->format('H:i:s');
        $this->followupDesc = $f->desc;
    }

    public function deleteThisFollowup($id)
    {
        $this->deleteFollowupId = $id;
    }

    public function dismissDeleteFollowup()
    {
        $this->deleteFollowupId = null;
    }

    public function deleteFollowup()
    {
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
            return redirect()->route('customers.show', $this->customer->id);
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
        $res = Followup::find($id)->setAsCalled($this->note);
        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->toggleCallerNote();
            $this->mount($this->customer->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setFollowupAsCancelled($id)
    {
        $res = Followup::find($id)->setAsCancelled($this->note);
        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->toggleCallerNote();
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

        /** @var Address */
        $address = Address::find($this->editedAddressId);
        $a = $address->editInfo(
            $this->EditedAddressType,
            $this->EditedLine1,
            $this->EditedLine2,
            $this->EditedCountry,
            $this->EditedCity,
            $this->EditedBuilding,
            $this->EditedFlat,
            $this->EditedArea,
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
            $this->EditedArea = null;
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
        $this->EditedArea = $a->area;
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
        $this->modelYear = $c->model_year;
        $this->insurancePayment  =  $c->insurance_payment;
        $this->paymentFreqs =  $c->payment_frequency;
        $this->renewalDate = Carbon::parse($c->renewal_date)->toDateString();
        $this->wiseInsured = $c->wise_insured;
        $this->insuranceCompanyId = $c->insurance_company_id;
        $this->models = CarModel::where('brand_id', $this->carBrand)->get();
        $this->cars = Car::where('car_model_id', $this->carModel)->get();
        $this->modelYears = CarPrice::where('car_id', $c->id)->get();
    }

    public function updateCar()
    {
        $this->validate([
            'CarCategory' => 'required|integer|exists:cars,id',
            'modelYear' => 'required|integer',
            'sumInsurance' => 'nullable|integer',
            'insurancePayment' => 'nullable|integer',
            'paymentFreqs' => 'nullable|in:' . implode(',', CustomerCar::PAYMENT_FREQS),
            'insuranceCompanyId' => 'nullable|integer|exists:insurance_companies,id',
            'renewalDate' => 'nullable|date',
            'wiseInsured' => 'nullable|boolean',

        ]);

        $renewalDate = $this->renewalDate ? Carbon::parse($this->renewalDate) : null;

        $c = CustomerCar::find($this->editedCarId);
        $c->editInfo(
            $this->CarCategory,
            $this->modelYear,
            $this->sumInsurance,
            $this->insurancePayment,
            $this->paymentFreqs,
            $this->insuranceCompanyId,
            $renewalDate,
            $this->wiseInsured
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
            $this->modelYear = null;
            $this->cars = null;
            $this->paymentFreqs = null;
            $this->insuranceCompanyId = null;
            $this->renewalDate = null;
            $this->wiseInsured = null;
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
        $this->modelYear = null;
        $this->paymentFreqs =  null;
        $this->models = null;
        $this->cars = null;
        $this->paymentFreqs = null;
        $this->insuranceCompanyId = null;
        $this->renewalDate = null;
        $this->wiseInsured = null;
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
            'modelYear' => 'required|integer',
            'sumInsurance' => 'nullable|integer',
            'insurancePayment' => 'nullable|integer',
            'paymentFreqs' => 'nullable|in:' . implode(',', CustomerCar::PAYMENT_FREQS),
            'insuranceCompanyId' => 'nullable|integer|exists:insurance_companies,id',
            'renewalDate' => 'nullable|date',
            'wiseInsured' => 'nullable|boolean',
        ], messages: []);

        $renewalDate = $this->renewalDate ? Carbon::parse($this->renewalDate) : null;

        $customer = Customer::find($this->customer->id);
        $c = $customer->addCar(
            $this->CarCategory,
            $this->modelYear,
            $this->sumInsurance,
            $this->insurancePayment,
            $this->paymentFreqs,
            $this->insuranceCompanyId,
            $renewalDate,
            $this->wiseInsured
        );
        if ($c) {
            $this->alert('success', 'Car added sucessfuly!');
            $this->CarCategory = null;
            $this->sumInsurance = null;
            $this->insurancePayment = null;
            $this->modelYear = null;
            $this->paymentFreqs = null;
            $this->insuranceCompanyId = null;
            $this->renewalDate = null;
            $this->wiseInsured = null;
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
            'area' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255'
        ]);

        /** @var Customer */
        $customer = Customer::find($this->customer->id);
        $a = $customer->addAddress(
            $this->addressType,
            $this->line1,
            $this->line2,
            $this->country,
            $this->city,
            $this->area,
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

    function generateUrl($fieldName, $property)
    {
        $idDoc_url = null;

        if (is_null($this->customer->$fieldName) && (is_null($this->$property))) {
            $idDoc_url = null;
        } elseif (!is_null($this->customer->$fieldName) && (is_null($this->$property))) {
            $idDoc_url = null;
        } elseif (!is_null($this->customer->$fieldName) && (!is_null($this->$property))) {
            if (is_string($this->$property)) {
                $this->$property = null;
                $idDoc_url = $this->customer->$fieldName;
            }
        }

        return $idDoc_url;
    }

    public function editInfo()
    {
        if (is_null($this->customer->id_doc) && (is_null($this->idDoc))) {
            $idDoc_url = null;
        } elseif (!is_null($this->customer->id_doc) && (is_null($this->idDoc))) {
            $idDoc_url = null;
        } elseif (!is_null($this->customer->id_doc) && (!is_null($this->idDoc))) {
            if (is_string($this->idDoc)) {
                $this->idDoc = null;
                $idDoc_url = $this->customer->id_doc;
            }
        }

        if (is_null($this->customer->driver_license_doc) && (is_null($this->driverLicenseDoc))) {
            $driverLicenseDoc_url = null;
        } elseif (!is_null($this->customer->driver_license_doc) && (is_null($this->driverLicenseDoc))) {
            $driverLicenseDoc_url = null;
        } elseif (!is_null($this->customer->driver_license_doc) && (!is_null($this->driverLicenseDoc))) {
            if (is_string($this->driverLicenseDoc)) {
                $this->driverLicenseDoc = null;
                $driverLicenseDoc_url = $this->customer->driver_license_doc;
            }
        }

        $this->validate([
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required|string|max:255',
            'ArabicFirstName' => 'nullable|string|max:255',
            'ArabicMiddleName' => 'nullable|string|max:255',
            'ArabicLastName' => 'nullable|string|max:255',
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
            'idDoc' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
            'driverLicenseDoc' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
        ]);

        $customer = Customer::find($this->customer->id);

        if (!is_string($this->idDoc) && !is_null($this->idDoc)) {
            $idDoc_url = $this->idDoc->store(Customer::FILES_DIRECTORY, 's3');
        }

        if (!is_string($this->driverLicenseDoc) && !is_null($this->driverLicenseDoc)) {
            $driverLicenseDoc_url = $this->driverLicenseDoc->store(Customer::FILES_DIRECTORY, 's3');
        }

        $c = $customer->editCustomer(
            $this->firstName,
            $this->lastName,
            $this->middleName,
            $this->ArabicFirstName,
            $this->ArabicMiddleName,
            $this->ArabicLastName,
            $this->bdate,
            $this->email,
            $this->gender,
            $this->maritalStatus,
            $this->idType,
            $this->idNumber,
            $this->nationalId,
            $this->profession_id,
            $this->salaryRange,
            $this->incomeSource,
            $idDoc_url,
            $driverLicenseDoc_url
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

        $this->firstName = $this->customer->first_name;
        $this->middleName = $this->customer->middle_name;
        $this->lastName = $this->customer->last_name;
        $this->ArabicFirstName = $this->customer->arabic_first_name;
        $this->ArabicMiddleName = $this->customer->arabic_middle_name;
        $this->ArabicLastName = $this->customer->arabic_last_name;
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
        $this->idDoc = $this->customer->id_doc;
        $this->driverLicenseDoc = $this->customer->driver_license_doc;
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

    public function updatedCarCategory()
    {
        if ($this->CarCategory !== '') {
            $this->modelYears = Car::find($this->CarCategory)->car_prices;
        }
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

    public function redirectToTask($id)
    {
        return redirect(Route('tasks.show', $id));
    }

    public function redirectToOffer($id)
    {
        return redirect(Route('offers.show', $id));
    }

    public function removeInterest($id)
    {
        $res = Interest::find($id)->delete();
        if ($res) {
            $this->mount($this->customer->id);
            $this->alert('success', 'Interest removed!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function editThisInterest($status, $lob)
    {
        $this->editInteresetSec = true;
        $this->editedLob  = $lob;
        $this->interested = $status;
    }

    public function closeEditInterest()
    {
        $this->editInteresetSec = false;
    }

    public function editInterest()
    {
        if ($this->interested === 'YES') {
            $this->interested = true;
        } else {
            $this->interested = false;
        }

        $this->validate([
            'editedLob' => 'required|in:' . implode(',', policy::LINES_OF_BUSINESS),
            'interested' => 'required|boolean',
            'interestNote' => 'nullable|string|max:255'
        ]);

        $res = $this->customer->addInterest($this->editedLob, $this->interested, $this->interestNote);

        if ($res) {
            $this->editInteresetSec = false;
            $this->editedLob = null;
            $this->interested = null;
            $this->interestNote = null;
            $this->mount($this->customer->id);
            $this->alert('success', 'Interest edited!');
        } else {
            $this->alert('failed', 'Server error');
        }
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
        $cities = City::all();
        $areas = Area::all();
        $phoneTypes = Phone::TYPES;
        $tasks = $this->customer->tasks;
        $offers = $this->customer->offers;
        $bankAccTypes = BankAccount::TYPES;
        $companies = Company::all();
        // dd($tasks);
        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;

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
            'tasks' => $tasks,
            'cities' => $cities,
            'areas' => $areas,
            'offers' => $offers,
            'bankAccTypes' => $bankAccTypes,
            'companies' => $companies,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS
        ]);
    }
}
