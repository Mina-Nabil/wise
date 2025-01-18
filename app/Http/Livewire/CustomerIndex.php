<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customers\Customer;
use App\Models\Customers\Status;
use App\Models\Customers\Profession;
use App\Models\Base\Country;
use App\Models\Users\User;
use App\Traits\AlertFrontEnd;
use Carbon\Carbon;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class CustomerIndex extends Component
{
    use WithPagination, AlertFrontEnd, ToggleSectionLivewire, WithFileUploads;

    public $search;

    public $addCustomerSection = false;
    public $ownerId;
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
    public $note;
    public $idDoc;
    public $idDoc2;
    public $driverLicenseDoc;
    public $driverLicenseDoc2;
    public $followupCallDateTime;

    public $addLeadSection;
    public $leadFirstName;
    public $leadMiddleName;
    public $leadLastName;
    public $leadArabicFirstName;
    public $leadArabicMiddleName;
    public $leadArabicLastName;
    public $LeadPhone;
    public $LeadNote;

    public $changeCustStatusId;
    public $changeCustStatusStatus;
    public $statusReason;
    public $statusNote;

    public $leadsImportFile;
    public $downloadUserLeadsID;

    public $statusFilter = false;

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
        if ($this->followupCallDateTime === '') {
            $this->followupCallDateTime = null;
        }

        $this->validate([
            'leadFirstName' => 'required|string|max:255',
            'leadMiddleName' => 'nullable|string|max:255',
            'leadLastName' => 'required|string|max:255',
            'leadArabicFirstName' => 'nullable|string|max:255',
            'leadArabicMiddleName' => 'nullable|string|max:255',
            'leadArabicLastName' => 'nullable|string|max:255',
            'LeadPhone' => 'required|string|max:255',
            'LeadNote' => 'nullable|string|max:255',
            'followupCallDateTime' => 'nullable|date_format:Y-m-d\TH:i',
            'ownerId' => 'nullable|integer|exists:users,id',
        ]);

        $res = Customer::newLead($this->leadFirstName, $this->leadLastName, $this->LeadPhone, $this->leadMiddleName, $this->leadArabicFirstName, $this->leadArabicMiddleName, $this->leadArabicLastName, owner_id: $this->ownerId, note: $this->LeadNote);


        if ($this->followupCallDateTime) {
            $fres = $res->addFollowup('Initial Contact', new \DateTime($this->followupCallDateTime), $this->LeadNote);
        } else {
            $fres = true;
        }

        if ($res && $fres) {
            $this->alert('success', 'Lead Added!');
            $this->toggleAddLead();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function clearIdDoc()
    {
        $this->idDoc = null;
    }

    public function clearIdDoc2()
    {
        $this->idDoc2 = null;
    }

    public function cleardriverLicenseDoc()
    {
        $this->driverLicenseDoc = null;
    }

    public function cleardriverLicenseDoc2()
    {
        $this->driverLicenseDoc2 = null;
    }


    public function addCustomer()
    {
        if ($this->followupCallDateTime === '') {
            $this->followupCallDateTime = null;
        }

        $this->validate([
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required|string|max:255',
            'ArabicFirstName' => 'nullable|string|max:255',
            'ArabicMiddleName' => 'nullable|string|max:255',
            'ArabicLastName' => 'nullable|string|max:255',
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
            'note' => 'nullable|string|max:255',
            'followupCallDateTime' => 'nullable|date_format:Y-m-d\TH:i',
            'ownerId' => 'nullable|integer|exists:users,id',
            'idDoc' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
            'driverLicenseDoc' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
            'idDoc2' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
            'driverLicenseDoc2' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',

        ]);

        $idDoc_url = null;
        if (!is_string($this->idDoc) && !is_null($this->idDoc)) {
            $idDoc_url = $this->idDoc->store(Customer::FILES_DIRECTORY, 's3');
        }

        $idDoc2_url = null;
        if (!is_string($this->idDoc2) && !is_null($this->idDoc2)) {
            $idDoc2_url = $this->idDoc2->store(Customer::FILES_DIRECTORY, 's3');
        }

        $driverLicenseDoc_url = null;
        if (!is_string($this->driverLicenseDoc) && !is_null($this->driverLicenseDoc)) {
            $driverLicenseDoc_url = $this->driverLicenseDoc->store(Customer::FILES_DIRECTORY, 's3');
        }

        $driverLicenseDoc2_url = null;
        if (!is_string($this->driverLicenseDoc2) && !is_null($this->driverLicenseDoc2)) {
            $driverLicenseDoc2_url = $this->driverLicenseDoc2->store(Customer::FILES_DIRECTORY, 's3');
        }

        $res = Customer::newCustomer(
            $this->ownerId,
            $this->firstName,
            $this->lastName,
            $this->gender,
            $this->email,
            $this->middleName,
            $this->ArabicFirstName,
            $this->ArabicMiddleName,
            $this->ArabicLastName,
            $this->bdate,
            $this->maritalStatus,
            $this->idType,
            $this->idNumber,
            $this->nationalId,
            $this->profession_id,
            $this->salaryRange,
            $this->incomeSource,
            id_doc: $idDoc_url,
            driver_license_doc: $driverLicenseDoc_url,
            id_doc_2: $idDoc2_url,
            driver_license_doc_2: $driverLicenseDoc2_url,
            note: $this->note
        );

        if ($this->followupCallDateTime) {
            $fres = $res->addFollowup('Initial Contact', new \DateTime($this->followupCallDateTime), $this->note);
        } else {
            $fres = true;
        }


        if ($res && $fres) {
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

    public function openStatusSection($customer_id)
    {
        $statuss = Customer::findOrFail($customer_id)?->status;
        if(!$statuss) return $this->changeThisStatus($customer_id, 'new');
        $this->changeCustStatusStatus = $statuss->status;
        $this->statusReason = $statuss->reason;
        $this->statusNote = $statuss->note;
        $this->changeCustStatusId = $customer_id;
    }

    public function redirectToShowPage($id)
    {
        return redirect(route('customers.show', $id));
    }

    ///import/export functions
    public function importLeads()
    {
        $this->validate([
            'leadsImportFile' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
        ]);
        $importedFile_url = $this->leadsImportFile->store('tmp', 'local');
        Customer::importLeads(storage_path('app/' . $importedFile_url));
        unlink(storage_path('app/' . $importedFile_url));
        $this->leadsImportFile = null;
        $this->toggleAddLead();
        $this->resetPage();
    }

    public function downloadLeadsFile()
    {
        $this->validate([
            'downloadUserLeadsID' => 'nullable|integer|exists:users,id',
        ]);

        return Customer::exportLeads($this->downloadUserLeadsID);
    }

    public function downloadTemplate()
    {
        return Customer::downloadTemplate();
    }

    //reseting page while searching
    public function updatingSearch()
    {
        $this->resetPage();
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
        $customers = Customer::userData($this->search, statusFilter: $this->statusFilter)->latest()->paginate(10);
        $users = User::all();

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
            'users' => $users,
        ]);
    }
}
