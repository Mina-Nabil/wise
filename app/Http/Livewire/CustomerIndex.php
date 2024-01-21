<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customers\Customer;
use App\Models\Customers\Status;
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
    public $firstName;
    public $middleName;
    public $lastName;
    public $ArabicFirstName;
    public $ArabicMiddleName;
    public $ArabicLastName;
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
    public $leadFirstName;
    public $leadMiddleName;
    public $leadLastName;
    public $leadArabicFirstName;
    public $leadArabicMiddleName;
    public $leadArabicLastName;
    public $LeadPhone;

    public $changeCustStatusId;
    public $changeCustStatusStatus;
    public $statusReason;
    public $statusNote;

    public function changeThisStatus($id, $status)
    {
        $this->changeCustStatusId = $id;
        $this->changeCustStatusStatus = $status;
    }

    public function closeChangeStatus()
    {
        $this->changeCustStatusId = null;
        $this->changeCustStatusStatus = null;
    }

    public function changeStatus()
    {
        $res = Customer::find($this->changeCustStatusId)->setStatus($this->changeCustStatusStatus, $this->statusReason, $this->statusNote);
        if ($res) {
            $this->alert('success', 'Status updated!');
            $this->changeCustStatusId = null;
            $this->changeCustStatusStatus = null;
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addLead()
    {
        $this->validate([
            'leadFirstName' => 'required|string|max:255',
            'leadMiddleName' => 'nullable|string|max:255',
            'leadLastName' => 'required|string|max:255',
            'leadArabicFirstName' => 'nullable|string|max:255',
            'leadArabicMiddleName' => 'nullable|string|max:255',
            'leadArabicLastName' => 'nullable|string|max:255',
            'LeadPhone' => 'required|string|max:255',
        ]);

        $customer = new Customer();
        $res = $customer->newLead($this->leadFirstName, $this->leadLastName, $this->LeadPhone, $this->leadMiddleName, $this->leadArabicFirstName, $this->leadArabicMiddleName, $this->leadArabicLastName, owner_id: auth()->id());
        if ($res) {
            $this->alert('success', 'Lead Added!');
            $this->toggleAddLead();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addCustomer()
    {
        $this->validate([
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required|string|max:255',
            'ArabicFirstName' => 'nullable|string|max:255',
            'ArabicMiddleName' => 'nullable|string|max:255',
            'ArabicLastName' => 'nullable|string|max:255',
            'arabic_name' => 'nullable|string|max:255',
            'bdate' => 'nullable|date',
            'email' => 'required|email',
            'gender' => 'required|in:' . implode(',', Customer::GENDERS),
            'maritalStatus' => 'nullable|in:' . implode(',', Customer::MARITALSTATUSES),
            'idType' => 'nullable|in:' . implode(',', Customer::IDTYPES),
            'idNumber' => 'nullable|string|max:255',
            'nationalId' => 'nullable|integer|exists:countries,id',
            'profession_id' => 'nullable|exists:professions,id',
            'salaryRange' => 'nullable|in:' . implode(',', Customer::SALARY_RANGES),
            'incomeSource' => 'nullable|in:' . implode(',', Customer::INCOME_SOURCES),
        ]);

        $customer = new Customer();
        $res = $customer->newCustomer(auth()->id(), $this->firstName, $this->lastName, $this->gender, $this->email, $this->middleName, $this->ArabicFirstName, $this->ArabicMiddleName, $this->ArabicLastName, $this->bdate, $this->maritalStatus, $this->idType, $this->idNumber, $this->nationalId, $this->profession_id, $this->salaryRange, $this->incomeSource);
        if ($res) {
            redirect()->route('customers.show', $res->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function toggleAddLead()
    {
        $this->toggle($this->addLeadSection);
    }

    public function toggleAddCustomer()
    {
        $this->toggle($this->addCustomerSection);
    }

    public function redirectToShowPage($id)
    {
        return redirect(route('customers.show', $id));
    }

    public function render()
    {
        $GENDERS = Customer::GENDERS;
        $MARITALSTATUSES = Customer::MARITALSTATUSES;
        $IDTYPES = Customer::IDTYPES;
        $SALARY_RANGES = Customer::SALARY_RANGES;
        $INCOME_SOURCES = Customer::INCOME_SOURCES;
        $professions = Profession::all();
        $customerStatus = Status::STATUSES;
        $countries = Country::all();
        $customers = Customer::userData($this->search)->paginate(10);

        return view('livewire.customer-index', [
            'customers' => $customers,
            'GENDERS' => $GENDERS,
            'MARITALSTATUSES' => $MARITALSTATUSES,
            'IDTYPES' => $IDTYPES,
            'professions' => $professions,
            'SALARY_RANGES' => $SALARY_RANGES,
            'INCOME_SOURCES' => $INCOME_SOURCES,
            'countries' => $countries,
            'customerStatus' => $customerStatus,
        ]);
    }
}
