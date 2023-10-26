<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Insurance\Company;
use App\Models\Insurance\CompanyEmail;
use App\Traits\AlertFrontEnd;
use Livewire\WithPagination;

class CompanyIndex extends Component
{
    use WithPagination, AlertFrontEnd;

    public $search;
    public $deleteInfo;
    public $newName;
    public $newNote;
    public $editThisComp;

    public $companyInfo;
    public $companyInfoName;
    public $companyInfoNote;

    public $newEmailType;
    public $newEmail;
    public $newEmailFname;
    public $newEmailLname;

    public $editableEmailId;
    public $editableEmailType;
    public $editableEmailIsPrim = false;
    public $editableEmail;
    public $editableEmailFname;
    public $editableEmailLname;

    public $deleteThisEmail;

    public $AddCompanySec = false;

    public function openAddCompany()
    {
        $this->AddCompanySec = true;
    }

    public function declineDeleteEmail()
    {
        $this->deleteThisEmail = null;
    }


    public function deleteEmail()
    {
        $email = CompanyEmail::find($this->deleteThisEmail);

        if ($email) {
            $email->delete();
            $this->alert('success', 'Email Deleted Successfuly!');
            $this->deleteThisEmail = null;
            $this->editRow($this->editThisComp);
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function confirmDelEmail($id)
    {
        $this->deleteThisEmail = $id;
    }

    public function closeAddCompany()
    {
        $this->AddCompanySec = false;
    }

    public function deleteThisComp($id, $name)
    {
        $this->deleteInfo = [$id, $name];
    }

    public function closeDelete()
    {
        $this->deleteInfo = null;
    }

    public function editRow($id)
    {
        $this->editThisComp = $id;
        $this->companyInfo = Company::findOrFail($id);
        $this->companyInfoName = $this->companyInfo->name;
        $this->companyInfoNote = $this->companyInfo->note;
    }

    public function editEmailRow($id)
    {
        $this->editableEmailId = $id;
        $email = CompanyEmail::findOrFail($id);

        $this->editableEmailType = $email->type;
        $this->editableEmail = $email->email;
        $this->editableEmailFname = $email->contact_first_name;
        $this->editableEmailLname = $email->contact_last_name;
    }

    public function closeEditEmailRow()
    {
        $this->editableEmailId = null;
        $this->editableEmailType = null;
        $this->editableEmail = null;
        $this->editableEmailFname = null;
        $this->editableEmailLname = null;
    }


    public function saveEmailRow()
    {
        $this->validate(
            [
                'editableEmailType' => 'required|string|max:255',
                'editableEmail' => 'required|email|max:255',
                'editableEmailFname' => 'required|string|max:255',
                'editableEmailLname' => 'required|string|max:255',
            ],
            [],
            [
                'editableEmailType' => 'Email Type',
                'editableEmail' => 'Email Address',
                'editableEmailFname' => 'First Name',
                'editableEmailLname' => 'Last Name',
            ],
        );

        $email = CompanyEmail::find($this->editableEmailId);

        if ($this->editableEmailIsPrim === 'primary') {
            $this->editableEmailIsPrim = true;
        };

        $e = $email->editInfo($this->editableEmailType, $this->editableEmail, $this->editableEmailIsPrim, $this->editableEmailFname, $this->editableEmailLname, null);

        if ($e) {
            $this->alert('success', 'Email Updated Succesfuly!');
            $this->closeEditEmailRow();
            $this->editRow($this->editThisComp);
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function closeEdit()
    {
        $this->editThisComp = null;
    }

    public function saveChanges()
    {
        $this->validate(
            [
                'companyInfoName' => 'required|string|max:255',
                'companyInfoNote' => 'string',
            ],
            [],
            [
                'companyInfoName' => 'Company Name',
                'companyInfoNote' => 'Note',
            ],
        );

        $company = Company::findOrFail($this->editThisComp);
        $success = $company->editInfo($this->companyInfoName, $this->companyInfoNote);

        if ($success) {
            $this->alert('success', 'Company updated!');
            $this->editRow($this->editThisComp);
        } else {
            $this->alert('failed', 'Server error!');
        }
    }

    public function addEmail()
    {
        $this->validate(
            [
                'newEmailType' => 'required|string|max:255',
                'newEmail' => 'required|email|max:255',
                'newEmailFname' => 'required|string|max:255',
                'newEmailLname' => 'required|string|max:255',
            ],
            [],
            [
                'newEmailType' => 'Email Type',
                'newEmail' => 'Email Address',
                'newEmailFname' => 'First Name',
                'newEmailLname' => 'Last Name',
            ],
        );

        /** @var Company */
        $company = Company::findOrFail($this->editThisComp);

        $a = $company->addEmail($this->newEmailType, $this->newEmail, true, $this->newEmailFname, $this->newEmailLname, null);

        // dd($a);

        if ($a) {
            $this->alert('success', 'Email Added Succesfuly!');
            $this->editRow($this->editThisComp);
            $this->newEmailType = null;
            $this->newEmail = null;
            $this->newEmailFname = null;
            $this->newEmailLname = null;
        } else {
            $this->alert('failed', 'Failed to Add!');
        }
    }

    public function add()
    {
        $this->validate(
            [
                'newName' => 'required|string|max:255|unique:insurance_companies,name',
                'newNote' => 'nullable|string',
            ],
            [],
            [
                'newName' => 'Company Name',
                'newNote' => 'Note',
            ],
        );

        $c = Company::newCompany($this->newName, $this->newNote);
        if ($c) {
            $this->alert('success', 'Company Added Succesfuly!');
            $this->newName = null;
            $this->newNote = null;
            $this->companyInfo = $c->id;
        } else {
            $this->alert('failed', 'Failed to Add!');
        }
    }

    public function delete()
    {
        try {
            Company::findOrFail($this->deleteInfo[0])->delete();

            $this->deleteInfo = null;

            $this->alert('success', 'Company Deleted Succesfuly!');
        } catch (\Exception $e) {
            $this->deleteInfo = null;
            $this->alert('failed', 'Failed to delete!');
        }
    }

    public function render()
    {
        $types = CompanyEmail::TYPES;

        $companies = Company::with('emails')
            ->searchBy($this->search)
            ->paginate(10);

        return view('livewire.company-index', [
            'companies' => $companies,
            'companyInfo' => $this->companyInfo,
            'types' => $types,
        ]);
    }
}
