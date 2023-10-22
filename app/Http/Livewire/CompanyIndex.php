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

    public function closeEdit()
    {
        $this->editThisComp = null;
    }

    public function saveChanges()
    {
        $this->validate([
            'companyInfoName' => 'required'
        ]);

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
        $this->validate([
            'newEmailType' => 'required',
            'newEmail' => 'required',
            // 'newEmailFname' => 'required',
            // 'newEmailLname' => 'required',
        ]);
        /** @var Company */
        $company = Company::findOrFail($this->editThisComp);

        $a = $company->addEmail($this->newEmailType, $this->newEmail, true, $this->newEmailFname, $this->newEmailLname, null);

        // dd($a);

        if ($a) {
            $this->alert('success', 'Email Added Succesfuly!');
            $this->editRow($this->editThisComp);
        } else {
            $this->alert('failed', 'Failed to Add!');
        }
    }

    public function add()
    {
        $this->validate([
            'newName' => 'required',
        ]);

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
