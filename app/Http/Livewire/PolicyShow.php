<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Policy;
use App\Models\Cars\Brand;
use App\Models\Insurance\PolicyCondition;

use Livewire\Component;

class PolicyShow extends Component
{
    public $policyId;

    public $editedScope;
    public $editedOperator;
    public $editedValue;
    public $editedRate;
    public $editedNote;

    public $policy_name;
    public $policy_business;
    public $policy_company_id;
    public $policy_note;

    public $addedScope;
    public $addedOperator;
    public $addedValue;
    public $addedRate;
    public $addedNote;

    public $newConditionSection = false;
    public $editedRowId;
    public $brands;



    public function openNewConditionSection()
    {
        $this->newConditionSection = true;
    }

    public function mount()
    {
        $policy = Policy::find($this->policyId);
        $brands = Brand::all();
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

        // update this condition using these variables with validation


        // $this->editedScope
        // $this->editedOperator
        // $this->editedValue
        // $this->editedRate
        // $this->editedNote
    }

    public function addCondition()
    {
        // $this->addedScope;
        // $this->addedOperator;
        // $this->addedValue;
        // $this->addedRate;
        // $this->addedNote;
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
            'policy_name' => $policy_name,
            'policy_business' => $policy_business,
            'policy_note' => $policy_note,
            'conditions' => $conditions,
            'editedRowId' => $this->editedRowId,
            'brands' => $this->brands,
        ]);
    }
}
