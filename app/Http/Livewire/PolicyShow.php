<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Policy;
use App\Models\Cars\Brand;
use App\Models\Cars\CarModel;
use App\Models\Base\Country;
use App\Models\Insurance\PolicyBenefit;
use App\Models\Insurance\PolicyCondition;
use App\Models\Insurance\GrossCalculation;
use App\Models\Payments\PolicyCommConf;
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
    public $addedOperator = 'e';
    public $addedValue;
    public $addedRate;
    public $addedNote;

    public $newConditionSection = false;
    public $editedRowId;
    public $brands;
    public $models;
    public $countries;

    public $changes = false;

    public $BENEFITS;
    public $editBenefitId;
    public $ebenefit;
    public $benefitValue;
    public $newBenefit;
    public $newValue;
    public $deleteBenefitId;

    public $calcId;
    public $editCalcId;
    public $eTitle;
    public $eType;
    public $eValue;
    public $deleteCalcId;
    public $newCalcTitle;
    public $newCalcType = '%';
    public $newCalcValue;

    public $confId;
    public $editConfId;
    public $eConfTitle;
    public $eConfType;
    public $eConfValue;
    public $eConfDue;
    public $eConfPen;
    public $eSalesOut;
    public $eMainPenalty;

    public $deleteConfId;
    public $newConfTitle;
    public $newConfType = '%';
    public $newConfValue;
    public $newConfDue;
    public $newConfPen;
    public $newSalesOut;
    public $newMainPenalty;

    public function addConf()
    {
        $this->validate([
            'newConfTitle' => 'required|string|max:255',
            'newConfType' => 'required|in:' . implode(",", GrossCalculation::TYPES),
            'newConfValue' => 'required|numeric',
            'newConfDue' => 'nullable|numeric',
            'newConfPen' => 'nullable|numeric|min:0|max:100',
        ]);

        $res = Policy::find($this->policyId)->addCommConf($this->newConfTitle, $this->newConfType, $this->newConfValue, $this->newConfDue, $this->newConfPen, $this->newSalesOut, $this->newMainPenalty);
        if ($res) {
            $this->mount();
            $this->newConfTitle = null;
            $this->newConfType = '%';
            $this->newConfValue = null;
            $this->newConfDue = null;
            $this->newConfPen = null;
            $this->newSalesOut = false;
            $this->newMainPenalty = false;
            $this->alert('success', 'Configuration added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editConf()
    {
        $this->validate([
            'eConfTitle' => 'required|string|max:255',
            'eConfType' => 'required|in:' . implode(",", GrossCalculation::TYPES),
            'eConfValue' => 'required|numeric',
            'eConfDue' => 'nullable|numeric',
            'eConfPen' => 'nullable|numeric|min:0|max:100',
        ]);

        $res = PolicyCommConf::find($this->editConfId)->editInfo($this->eConfTitle, $this->eConfType, $this->eConfValue, $this->eConfDue, $this->eConfPen, $this->eSalesOut, $this->eMainPenalty);
        if ($res) {
            $this->mount();
            $this->editConfId = null;
            $this->eConfTitle = null;
            $this->eConfType = null;
            $this->eConfValue = null;
            $this->eConfDue = null;
            $this->eConfPen = null;
            $this->eSalesOut = false;
            $this->eMainPenalty = false;
            $this->alert('success', 'Configurations updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deleteThisConf($id)
    {
        $this->deleteConfId = $id;
    }

    public function dismissDeleteConf()
    {
        $this->deleteConfId = null;
    }

    public function deleteConf()
    {
        $res = PolicyCommConf::find($this->deleteConfId)->delete();
        if ($res) {
            $this->mount();
            $this->deleteConfId = null;
            $this->alert('success', 'Configuration deleted!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editThisConf($id)
    {
        $this->editConfId = $id;
        $g = PolicyCommConf::find($id);
        $this->eConfTitle = $g->title;
        $this->eConfType = $g->calculation_type;
        $this->eConfValue = $g->value;
        $this->eConfDue = $g->due_penalty;
        $this->eConfPen = $g->penalty_percent;
        $this->eSalesOut = $g->sales_out_only ? true : false;
        $this->eMainPenalty = $g->is_main_penalty ? true : false;
    }

    public function closeEditConf()
    {
        $this->editConfId = null;
        $this->eConfTitle = null;
        $this->eConfType = null;
        $this->eConfValue = null;
        $this->eConfDue = null;
        $this->eConfPen = null;
    }

    public function addCalc()
    {
        $this->validate([
            'newCalcTitle' => 'required|string|max:255',
            'newCalcType' => 'required|in:' . implode(",", GrossCalculation::TYPES),
            'newCalcValue' => 'required|numeric',
        ]);

        $res = Policy::find($this->policyId)->addGrossCalculation($this->newCalcTitle, $this->newCalcType, $this->newCalcValue);
        if ($res) {
            $this->mount();
            $this->newCalcTitle = null;
            $this->newCalcType = '%';
            $this->newCalcValue = null;
            $this->alert('success', 'Calculation added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editCalc()
    {
        $this->validate([
            'eTitle' => 'required|string|max:255',
            'eType' => 'required|in:' . implode(",", GrossCalculation::TYPES),
            'eValue' => 'required|numeric',
        ]);

        $res = GrossCalculation::find($this->editCalcId)->editInfo($this->eTitle, $this->eType, $this->eValue);
        if ($res) {
            $this->mount();
            $this->editCalcId = null;
            $this->eTitle = null;
            $this->eType = null;
            $this->eValue = null;
            $this->alert('success', 'Calculation updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deleteThisCalc($id)
    {
        $this->deleteCalcId = $id;
    }

    public function dismissDeleteCalculation()
    {
        $this->deleteCalcId = null;
    }

    public function deleteCalc()
    {
        $res = GrossCalculation::find($this->deleteCalcId)->delete();
        if ($res) {
            $this->mount();
            $this->deleteCalcId = null;
            $this->alert('success', 'Calculation deleted!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editThisCalc($id)
    {
        $this->editCalcId = $id;
        $g = GrossCalculation::find($id);
        $this->eTitle = $g->title;
        $this->eType = $g->calculation_type;
        $this->eValue = $g->value;
    }

    public function closeEditCalc()
    {
        $this->editCalcId = null;
        $this->eTitle = null;
        $this->eType = null;
        $this->eValue = null;
    }

    public function addBenefit()
    {
        $this->validate([
            'newBenefit' =>  'required|in:' . implode(",", PolicyBenefit::BENEFITS),
            'newValue' => 'required|string|max:255'
        ]);
        $res = Policy::find($this->policyId)->addBenefit($this->newBenefit, $this->newValue);
        if ($res) {
            $this->mount();
            $this->newBenefit = null;
            $this->newValue = null;
            $this->alert('success', 'Benefit added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editBenefit()
    {
        $res = PolicyBenefit::find($this->editBenefitId)->editInfo($this->ebenefit, $this->benefitValue);
        if ($res) {
            $this->mount();
            $this->ebenefit = null;
            $this->benefitValue = null;
            $this->editBenefitId = null;
            $this->alert('success', 'Benefit updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deleteThisBenefit($id)
    {
        $this->deleteBenefitId = $id;
    }

    public function dismissDeleteOption()
    {
        $this->deleteBenefitId = null;
    }

    public function deleteBenefit()
    {
        $res = PolicyBenefit::find($this->deleteBenefitId)->delete();
        if ($res) {
            $this->mount();
            $this->deleteBenefitId = null;
            $this->alert('success', 'Benefit deleted!');
        } else {
            $this->alert('failed', 'server error');
        }
    }


    public function editThisBenefit($id)
    {
        $this->editBenefitId = $id;
        $b = PolicyBenefit::find($id);
        $this->ebenefit = $b->benefit;
        $this->benefitValue = $b->value;
    }

    public function closeEditBenefit()
    {
        $this->editBenefitId = null;
        $this->ebenefit = null;
        $this->benefitValue = null;
    }

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
        $this->addedOperator = 'e';
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
        $this->BENEFITS = PolicyBenefit::BENEFITS;

        foreach ($this->BENEFITS as $BENEFIT) {
            if (!in_array($BENEFIT, $policy->benefits->pluck('benefit')->toArray())) {
                $this->newBenefit = $BENEFIT;
                break;
            }
        }
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
        $calcTypes = GrossCalculation::TYPES;

        // $this->addedScope = 'age';


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
            'calcTypes' => $calcTypes
        ]);
    }
}
