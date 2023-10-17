<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Insurance\Company;
use Livewire\WithPagination;

class CompanyIndex extends Component
{
    use WithPagination;

    public $search;
    public $deleteInfo;
    public $newName;
    public $newNote;
    public $editThisComp;

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
    }

    public function closeEdit()
    {
        $this->editThisComp = null;
    }

    public function add()
    {
        $c = Company::newCompany($this->newName, $this->newNote);
        if ($c) {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Company Added Succesfuly!',
                'type' => 'success',
            ]);
            $this->newName = null;
            $this->newNote = null;
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
        $companies = Company::with('emails')
            ->searchBy($this->search)
            ->paginate(10);

        return view('livewire.company-index', ['companies' => $companies]);
    }
}
