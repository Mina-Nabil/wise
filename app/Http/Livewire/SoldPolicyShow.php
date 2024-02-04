<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
use App\models\Business\SoldPolicyBenefit;
use App\models\Business\SoldPolicyExclusion;
use App\models\Insurance\PolicyBenefit;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use PhpParser\Node\Expr\FuncCall;

class SoldPolicyShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire;

    public $soldPolicy;

    public $deleteBenefitId;
    public $benefitId;  //for edit
    public $eBenefit;   //edited benefit
    public $eValue;     //edited value
    public $newBenefit;
    public $newValue;
    public $newBenefitSec = false;

    public $deleteExcId;
    public $excId;
    public $eExcTitle;  //edited exc title
    public $eExcValue;  //edited exc value
    public $newExcTitle;
    public $newExcValue;
    public $newExcSection = false;

    //benefits functions
    public function openNewBenefitSec()
    {
        $this->newBenefitSec = true;
    }

    public function closeNewBenefitSec()
    {
        $this->newBenefitSec = false;
        $this->newBenefit = null;
        $this->newValue = null;
    }

    public function addBenefit()
    {
        $this->validate([
            'newBenefit' => 'required|in:' . implode(',', PolicyBenefit::BENEFITS),
            'newValue' => 'required|string|max:255'
        ]);

        $res = $this->soldPolicy->addBenefit($this->newBenefit, $this->newValue);
        if ($res) {
            $this->closeNewBenefitSec();
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Benefit added!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function editThisBenefit($id)
    {
        $this->benefitId = $id;
        $b = SoldPolicyBenefit::find($id);
        $this->eBenefit = $b->benefit;
        $this->eValue = $b->value;
    }

    public function closeEditBenefit()
    {
        $this->benefitId = null;
        $this->eBenefit = null;
        $this->eValue = null;
    }

    public function editBenefit()
    {
        $this->validate([
            'eBenefit' => 'required|in:' . implode(',', PolicyBenefit::BENEFITS),
            'eValue' => 'required|string|max:255'
        ]);

        $res = SoldPolicyBenefit::find($this->benefitId)->editInfo($this->eBenefit, $this->eValue);
        if ($res) {
            $this->closeEditBenefit();
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Benefit Updated!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function deleteThisBenefit($id)
    {
        $this->deleteBenefitId = $id;
    }

    public function dismissDeleteBenefit()
    {
        $this->deleteBenefitId = null;
    }

    public function deleteBenefit()
    {
        $res = SoldPolicyBenefit::find($this->deleteBenefitId)->delete();
        if ($res) {
            $this->deleteBenefitId = null;
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Benefit deleted!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    //exclutions functions
    public function openAddExcSec()
    {
        $this->newExcSection = true;
    }

    public function closeAddExcSec()
    {
        $this->newExcSection = false;
        $this->newExcTitle = null;
        $this->newExcValue = null;
    }

    public function addExc()
    {
        $this->validate([
            'newExcTitle' => 'required|string|max:255',
            'newExcValue' => 'required|string|max:255'
        ]);

        $res = $this->soldPolicy->addExclusion($this->newExcTitle, $this->newExcValue);
        if ($res) {
            $this->closeAddExcSec();
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Exclusion added!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function editThisExc($id)
    {
        $this->excId = $id;
        $e = SoldPolicyExclusion::find($id);
        $this->eExcTitle = $e->title;
        $this->eExcValue = $e->value;
    }

    public function closeEditExc()
    {
        $this->excId = null;
        $this->eExcTitle = null;
        $this->eExcValue = null;
    }

    public function editExc()
    {
        $this->validate([
            'eExcTitle' => 'required|string|max:255',
            'eExcValue' => 'required|string|max:255'
        ]);

        $res = SoldPolicyExclusion::find($this->excId)->editInfo($this->eExcTitle, $this->eExcValue);

        if ($res) {
            $this->closeEditExc();
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Exclusion updated!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function deleteThisExc($id)
    {
        $this->deleteExcId = $id;
    }

    public function dismissDeleteExc()
    {
        $this->deleteExcId = null;
    }

    public function deleteExc()
    {
        $res = SoldPolicyExclusion::find($this->deleteExcId)->delete();
        if ($res) {
            $this->deleteExcId = null;
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Exclusions deleted!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }



    public function mount($id)
    {
        $this->soldPolicy = SoldPolicy::find($id);
    }

    public function render()
    {
        $BENEFITS = PolicyBenefit::BENEFITS;
        return view('livewire.sold-policy-show', [
            'BENEFITS' => $BENEFITS
        ]);
    }
}
