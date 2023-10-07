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
    public $policy;
    public $linesOfBusiness;
    public $scopes;
    public $operators;
    public $companies;
    public $changesMade = false;
    public $policy_name;
    public $policy_business;
    public $policy_company_id;
    public $policy_note;

    public function mount()
    {
        $this->policy = Policy::find($this->policyId);
        $policy = Policy::find($this->policyId);
        $this->policy_name = $policy->name;
        $this->policy_business = $policy->business;
        $this->policy_company_id =$policy->company_id ;
        $this->policy_note = $policy->note;


        $this->linesOfBusiness = Policy::LINES_OF_BUSINESS;
        $this->scopes = PolicyCondition::SCOPES;
        $this->operators = PolicyCondition::OPERATORS;
        $this->companies = Company::all();

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
    }

    public function markAsChanged()
    {
        $this->changesMade = true;
    }

    public function deleteCondition($index)
    {
        // Fetch the condition by index or identifier
        $conditionToDelete = $this->policy->conditions[$index];

        // Delete the condition
        $conditionToDelete->delete();

        // Optionally, you can add a success message or perform any other actions after deletion.

        // // Refresh the Livewire component to reflect the updated conditions
        session()->flash('success', 'Condition Deleted successfully.');

        return redirect(route('policies.show', $this->policy->id));
    }

    public function bulkEdit()
    {
        if ($this->changesMade) {
            foreach ($this->policy->conditions as $index => $condition) {
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
        Policy::find($this->policy->id)->editInfo(
            $this->policy_name,
            $this->policy_business,
            $this->policy_company_id,
            $this->policy_note
        );
        
        session()->flash('success', 'Changes saved successfully!');

        $this->changesMade = false;

        // Optionally, you can add a success message or perform any other actions after editing.
    }

    public function render()
    {
        return view('livewire.policy-show', [
            'linesOfBusiness' => $this->linesOfBusiness,
            'companies' => $this->companies,
            'scopes' => $this->scopes,
            'operators' => $this->operators,
            'policy' => $this->policy,
            'policy_name' => $this->policy_name,
            'policy_business' => $this->policy_business,
            'policy_company_id' => $this->policy_company_id,
            'policy_note' => $this->policy_note,
        ]);
    }
}
