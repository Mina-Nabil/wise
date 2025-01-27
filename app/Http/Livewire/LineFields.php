<?php

namespace App\Http\Livewire;

use App\Models\Insurance\LineField;
use App\Models\Insurance\Policy;
use App\Traits\AlertFrontEnd;
use Livewire\Component;

class LineFields extends Component
{
    use AlertFrontEnd;
    public $showLineFields;
    public $fields = [];

    protected $queryString = ['showLineFields'];

    public $isOpenAddField= null; //carry the line of business
    public $isOpenEditField= null;
    public $fieldName;


    public $isConformDelete = null;

    public function confirmDelete($id)
    {
        $this->isConformDelete = $id;
    }

    public function closeDelete()
    {
        $this->isConformDelete = null;
    }

    public function deleteField()
    {
        $res = LineField::find($this->isConformDelete)->delete();
        if ($res) {
            $this->isConformDelete = null;
            $this->mount();
            $this->alert('success','deleted');
        }else{
            $this->alert('failed','server error');
        }
    }

    public function showLineFields($line_of_business)
    {
        if ($this->showLineFields === $line_of_business) {
            $this->showLineFields = null;
            return ;
        }
        $this->showLineFields = $line_of_business;

        $this->fields = LineField::ByLineOfBusiness($line_of_business)->get();
    }

    public function mount()
    {
        $this->showLineFields = request()->query('showLineFields', $this->showLineFields);
        $this->fields = LineField::ByLineOfBusiness($this->showLineFields)->get();

    }

    public function openEditField($id)
    {
        $this->isOpenEditField = LineField::findOrFail($id);
        $this->fieldName = $this->isOpenEditField->field;

    }

    public function closeEditField()
    {
        $this->isOpenEditField = null;
        $this->fieldName = null;
    }

    public function editField(){
        $this->validate([
            'fieldName' => 'required|string',
        ]);
        $lineField = $this->isOpenEditField->editField($this->fieldName);

        if ($lineField) {
            $this->fieldName = '';
            $this->showLineFields = $this->isOpenEditField->line_of_business;
            $this->mount();
            $this->closeEditField();
        }else{
            $this->addError('fieldName', 'Failed to edit field');
        }
    }

    public function openAddField($line_of_business)
    {
        $this->isOpenAddField = $line_of_business;
    }

    public function closeAddField()
    {
        $this->isOpenAddField = null;
    }

    public function addField()
    {
        $this->validate([
            'fieldName' => 'required|string',
        ]);
        $lineField = LineField::newLineField($this->isOpenAddField, $this->fieldName);

        if ($lineField) {
            $this->fieldName = '';
            $this->showLineFields = $this->isOpenAddField;
            $this->mount();
            $this->closeAddField();
        }else{
            $this->addError('fieldName', 'Failed to add field');
        }
    }



    public function render()
    {
        $LINE_OF_BUSINESSES = Policy::LINES_OF_BUSINESS;
        return view('livewire.line-fields',[
            'LINE_OF_BUSINESSES' => $LINE_OF_BUSINESSES
        ]);
    }
}
