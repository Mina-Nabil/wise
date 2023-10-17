<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Insurance\Company;
use App\Models\Insurance\CompanyEmail;
use Livewire\WithPagination;

class CompanyIndex extends Component
{
    use WithPagination;

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
            'companyInfoName' => 'required',
            'companyInfoNote' => 'required',
        ]);

        $company = Company::findOrFail($this->editThisComp);
        $success = $company->editInfo($this->companyInfoName, $this->companyInfoNote);

        if ($success) {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Company information updated successfully!',
                'type' => 'success',
            ]);
            $this->editRow($this->editThisComp);

        } else {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Failed to update company information!',
                'type' => 'failed',
            ]);
        }
    }

    public function addEmail()
    {
        $this->validate([
            'newEmailType' => 'required',
            'newEmail' => 'required',
            'newEmailFname' => 'required',
            'newEmailLname' => 'required',
        ]);

        $company = Company::findOrFail($this->editThisComp);

        $a = $company->addEmail($this->newEmailType, $this->newEmail, true, $this->newEmailFname, $this->newEmailLname, null);

        // dd($a);

        if ($a) {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Email Added Succesfuly!',
                'type' => 'success',
            ]);
            $this->editRow($this->editThisComp);
        } else {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Failed to Add!',
                'type' => 'failed',
            ]);
        }
    }

    public function add()
    {
        $this->validate([
            'newName' => 'required',
            'newNote' => 'required',
        ]);

        $c = Company::newCompany($this->newName, $this->newNote);
        if ($c) {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Company Added Succesfuly!',
                'type' => 'success',
            ]);
            $this->newName = null;
            $this->newNote = null;
            $this->companyInfo = $c->id;
        } else {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Failed to Add!',
                'type' => 'failed',
            ]);
        }
    }

    public function delete()
    {
        try {
            Company::findOrFail($this->deleteInfo[0])->delete();

            $this->deleteInfo = null;

            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Company Deleted Succesfuly!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            $this->deleteInfo = null;
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Failed to delete!',
                'type' => 'failed',
            ]);
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
