<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyCondition;
use App\Models\Insurance\Company;

use Livewire\Component;

class PolicyShow extends Component
{
    public $policyId;

    public $selectedScopes = [];
    public $selectedOperators = [];
    public $newValues = [];
    public $newRates = [];
    public $newNotes = [];
    public $launchedPolicy;
    // public $scopes;
    // public $operators;
    // public $companies;
    public $changesMade = false;
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

    

    public function openNewConditionSection()
    {
        $this->newConditionSection = true;
    }

    public function mount()
    {
        $this->launchedPolicy = Policy::find($this->policyId);
        $policy = Policy::find($this->policyId);
        $this->policy_name = $policy->name;
        $this->policy_business = $policy->business;
        $this->policy_company_id = $policy->company_id;
        $this->policy_note = $policy->note;
    }

    public function addCondition()
    {
        // $this->validate([

        // ]);

        $policy = Policy::find($this->launchedPolicy->id);
        $policy->addCondition(
            $this->addedScope,
            $this->addedOperator,
            $this->addedValue,
            '1',
            $this->addedRate,
            $this->addedNote
        );

        // $this->selectedScopes[] = $this->addedScope;
        // $this->selectedOperators[] = $this->addedOperator;
        // $this->newValues[] = $this->addedValue;
        // $this->newRates[] = $this->addedRate;
        // $this->newNotes[] = $this->addedNote;

        session()->flash('success', 'Condition Added successfully.');
        
        // $this->policy = Policy::find($this->policyId);
        // return redirect(route('policies.show', $this->policy->id));
    }

    public function markAsChanged()
    {
        $this->changesMade = true;
    }

    public function deleteCondition($index)
    {
        // Fetch the condition by index or identifier
        $conditionToDelete = $this->launchedPolicy->conditions[$index];

        // Delete the condition
        $conditionToDelete->delete();

        // Optionally, you can add a success message or perform any other actions after deletion.

        // // Refresh the Livewire component to reflect the updated conditions
        session()->flash('success', 'Condition Deleted successfully.');

    }

    public function bulkEdit()
    {

        if ($this->changesMade) {
            foreach ($this->launchedPolicy->conditions as $index => $condition) {
                $condition->update([
                    'scope' => $this->selectedScopes[$index],
                    'operator' => $this->selectedOperators[$index],
                    'value' => $this->newValues[$index],
                    'rate' => $this->newRates[$index],
                    'note' => $this->newNotes[$index],
                ]);
            }
        }
        $policyEdit = new Policy;
        Policy::find($this->launchedPolicy->id)->editInfo(
            $this->policy_company_id,
            $this->policy_name,
            $this->policy_business,
            $this->policy_note
        );

        

        session()->flash('success', 'Changes saved successfully!');

        $this->changesMade = false;

        // Optionally, you can add a success message or perform any other actions after editing.
    }

    public function render()
    {
        
        $policy = Policy::find($this->policyId);
        $policy_name = $policy->name;
        $policy_business = $policy->business;
        $policy_company_id = $policy->company_id;
        $policy_note = $policy->note;

        $this->addedScope = 'age';
        $this->addedOperator = 'e';


        $linesOfBusiness = Policy::LINES_OF_BUSINESS;
        // dd($linesOfBusiness);
        $scopes = PolicyCondition::SCOPES;
        $operators = PolicyCondition::OPERATORS;
        $companies = Company::all();

        // Fetch the conditions related to the policy (assuming $policy is available)
        $conditions = $policy->conditions;

        // Initialize arrays with old values
        foreach ($conditions as $condition) {
            $this->selectedScopes[] = $condition->scope;
            $this->selectedOperators[] = $condition->operator;
            $this->newValues[] = $condition->value;
            $this->newRates[] = $condition->rate;
            $this->newNotes[] = $condition->note;
        }

        return view('livewire.policy-show', [
            'linesOfBusiness' => $linesOfBusiness,
            'companies' => $companies,
            'scopes' => $scopes,
            'operators' => $operators,
            'policy' => $policy,
            'policy_name' => $policy_name,
            'policy_business' => $policy_business,
            'policy_company_id' => $policy_company_id,
            'policy_note' => $policy_note,
        ]);
    }
}
