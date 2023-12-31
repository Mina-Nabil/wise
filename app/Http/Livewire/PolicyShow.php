<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Policy;
use App\Models\Cars\Brand;
use App\Models\Cars\CarModel;
use App\Models\Cars\Country;
use App\Models\Insurance\PolicyCondition;
use App\Traits\AlertFrontEnd;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PolicyShow extends Component
{
    use AlertFrontEnd;

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
        $this->validate(
            [
                'policyName' => 'required|string',
                'policyBusiness' =>  'required|in:' . implode(",", Policy::LINES_OF_BUSINESS),
                'policyNote' => 'nullable|string'
            ]
        );
        $policy = Policy::find($this->policyId);

        $p = $policy->editInfo(
            $this->policyName,
            $this->policyBusiness,
            $this->policyNote
        );

        if ($p) {
            $this->alert('success', 'Policy updated!');
            $this->changes = false;
        } else {
            $this->alert('failed', 'Failed updating Policy!');
        }
    }

    public function openNewConditionSection()
    {
        $this->newConditionSection = true;
    }

    public function closeNewConditionSection()
    {
        $this->newConditionSection = false;
        $this->addedScope = null;
        $this->addedOperator = null;
        $this->addedValue = null;
        $this->addedRate = null;
        $this->addedNote = null;
    }

    public function deleteCondition($id)
    {
        $policy = PolicyCondition::find($id);
        $p = $policy->deleteCondition();
        if ($p) {
            $this->alert('success', 'Condition Deleted!');
        } else {
            $this->alert('failed', 'Server error!');
        }
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
        if (!$con) {
            $this->alert('failed', 'Server error!');
        } else {
            $this->editedScope = $con->scope;
            $this->editedOperator = $con->operator;
            $this->editedValue = $con->value;
            $this->editedRate = $con->rate;
            $this->editedNote = $con->note;

            $this->editedRowId = $id;
        }
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
            'editedValue' => 'required',
            'editedRate' => 'required|numeric|between:0,100'
        ], [], [
            'editedScope' => 'Scope',
            'editedOperator' => 'operator',
            'editedValue' => 'Value',
            'editedRate' => 'Rate',
        ]);

        /** @var PolicyCondition */
        $policyCondition = PolicyCondition::findOrFail($id);
        $p = $policyCondition->editInfo($this->editedScope, $this->editedOperator, $this->editedValue, $this->editedRate, $this->editedNote);

        if ($p) {
            $this->alert('success', 'Condition Updated!');
            $this->closeEditRow();
        } else {
            $this->alert('failed', 'Server error!');
        }
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
        $p = $pol->addCondition($this->addedScope, $this->addedOperator, $this->addedValue, $this->addedRate, $this->addedNote);

        if ($p) {
            $this->alert('success', 'Condition Added!');
            $this->closeNewConditionSection();
        } else {
            $this->alert('failed', 'Server error!');
        }
    }

    public function moveConditionUp($id)
    {
        /** @var PolicyCondition */
        $con = PolicyCondition::findOrFail($id);
        $c = $con->moveUp();

        if ($c) {
            $this->alert('success', 'Condition moved Up!');
            $this->closeNewConditionSection();
        } else {
            $this->alert('failed', 'Server error!');
        }
    }

    public function moveConditionDown($id)
    {
        /** @var PolicyCondition */
        $con = PolicyCondition::findOrFail($id);
        $c = $con->moveDown();

        if ($c) {
            $this->alert('success', 'Condition moved Down!');
            $this->closeNewConditionSection();
        } else {
            $this->alert('failed', 'Server error!');
        }
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
