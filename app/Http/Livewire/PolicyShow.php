<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Policy;
use App\Models\Cars\Brand;
use App\Models\Cars\CarModel;
use App\Models\Cars\Country;
use App\Models\Insurance\PolicyCondition;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PolicyShow extends Component
{
    public $policyId;

    public $editedScope;
    public $editedOperator;
    public $editedValue;
    public $editedRate;
    public $editedNote;

    public $policyName;
    public $policyBusiness;
    public $policyNote;

    public $addedScope;
    public $addedOperator;
    public $addedValue;
    public $addedRate;
    public $addedNote;

    public $newConditionSection = false;
    public $editedRowId;
    public $brands;
    public $models;
    public $countries;

    public $changes = false;


    public function updated($propertyName)
    {
        if (in_array($propertyName, ['policyName', 'policyBusiness', 'policyNote'])) {
            $this->changes = true;
        }
    }

    public function save()
    {
        $policy = Policy::find($this->policyId);

        $policy->editInfo(
            $this->policyName,
            $this->policyBusiness,
            $this->policyNote
        );
        $this->changes = false;
    }

    public function openNewConditionSection()
    {
        $this->newConditionSection = true;
    }

    public function closeNewConditionSection()
    {
        $this->newConditionSection = false;
    }

    public function mount()
    {
        $policy = Policy::find($this->policyId);
        $this->policyName = $policy->name;
        $this->policyBusiness = $policy->business;
        $this->policyNote = $policy->note;
        $this->brands = Brand::all();
        $this->models = CarModel::all();
        $this->countries = Country::all();
        $this->addedScope = 'age';
    }

    public function editRow($id)
    {

        $con = PolicyCondition::find($id);
        $this->editedScope = $con->scope;
        $this->editedOperator = $con->operator;
        $this->editedValue = $con->value;
        $this->editedRate = $con->rate;
        $this->editedNote = $con->note;

        $this->editedRowId = $id;
    }

    public function closeEditRow()
    {

        $this->editedRowId = null;
    }

    public function editCondition($id)
    {

        $this->validate([
            'editedScope' => 'required|in:' . implode(",", PolicyCondition::SCOPES),
            'editedOperator' => 'required|in:' . implode(",", PolicyCondition::OPERATORS),
            'editedValue' => 'required|numeric',
            'editedRate' => 'required|numeric|between:0,100'
        ]);

        /** @var PolicyCondition */
        $policyCondition = PolicyCondition::findOrFail($id);
        $policyCondition->editInfo($this->editedScope, $this->editedOperator, $this->editedValue, $this->editedRate, $this->editedNote);
        $this->closeEditRow();
    }

    public function addCondition()
    {
        $this->validate([
            'addedScope' => 'required|in:' . implode(",", PolicyCondition::SCOPES),
            'addedOperator' => 'required|in:' . implode(",", PolicyCondition::OPERATORS),
            'addedValue' => 'required|numeric',
            'addedRate' => 'required|numeric|between:0,100'
        ]);

        /** @var Policy */
        $pol = Policy::findOrFail($this->policyId);
        $pol->addCondition($this->addedScope, $this->addedOperator, $this->addedValue, $this->addedRate, $this->addedNote);
        $this->closeNewConditionSection();
    }

    public function moveConditionUp($id)
    {
        /** @var PolicyCondition */
        $con = PolicyCondition::findOrFail($id);
        $con->moveUp();
    }

    public function moveConditionDown($id)
    {
       /** @var PolicyCondition */
       $con = PolicyCondition::findOrFail($id);
       $con->moveDown();
    }

    public function render()
    {

        $policy = Policy::find($this->policyId);
        $policy_name = $policy->name;
        $policy_business = $policy->business;
        $policy_note = $policy->note;

        // $this->addedScope = 'age';
        $this->addedOperator = 'e';


        $linesOfBusiness = Policy::LINES_OF_BUSINESS;
        $scopes = PolicyCondition::SCOPES;
        $operators = PolicyCondition::OPERATORS;


        // Fetch the conditions related to the policy (assuming $policy is available)
        $conditions = $policy->conditions;


        return view('livewire.policy-show', [
            'linesOfBusiness' => $linesOfBusiness,
            'scopes' => $scopes,
            'operators' => $operators,
            'policy' => $policy,
            'policyName' => $this->policyName,
            'policy_business' => $this->policyBusiness,
            'policy_note' => $this->policyNote,
            'conditions' => $conditions,
            'editedRowId' => $this->editedRowId,
            'brands' => $this->brands,
            'models' => $this->models,
            'countries' => $this->countries,
        ]);
    }
}
