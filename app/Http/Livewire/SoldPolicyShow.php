<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
use App\models\Business\SoldPolicyBenefit;
use App\models\Business\SoldPolicyExclusion;
use App\models\Insurance\PolicyBenefit;
use App\models\Offers\OfferOption;
use App\Models\Tasks\TaskAction;
use App\Models\Tasks\TaskField;
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

    public $editPaymentInfoSection = false;
    public $insured_value;
    public $net_rate;
    public $net_premium;
    public $gross_premium;
    public $installements_count;
    public $payment_frequency;
    
    public $actions = [];
    public $fiels = [];

    public function removeAcion($index)
    {
        unset($this->actions[$index]);
        $this->actions = array_values($this->actions);
    }

    public function addAnotherAction()
    {
        $this->actions[] = ['column_name' => '', 'value' => ''];
    }

    public function removeField($index)
    {
        unset($this->fiels[$index]);
        $this->fiels = array_values($this->fiels);
    }

    public function addAnotherField()
    {
        $this->fiels[] = ['title' => '', 'value' => ''];
    }

    //paymentInfo
    public function togglePaymentInfoSection(){
        $this->toggle($this->editPaymentInfoSection);
    }

    public function editPaymentInfo(){
        $this->validate([
            'insured_value' => 'required|numeric',
            'net_rate' => 'required|numeric',
            'net_premium' => 'required|numeric',
            'gross_premium' => 'required|numeric',
            'installements_count' => 'required|numeric',
            'payment_frequency' => 'nullable|in:' . implode(',', OfferOption::PAYMENT_FREQS),
        ]);

        $res = $this->soldPolicy->updatePaymentInfo(
            $this->insured_value,
            $this->net_rate,
            $this->net_premium,
            $this->gross_premium,
            $this->installements_count,
            $this->payment_frequency
        );

        if ($res) {
            $this->togglePaymentInfoSection();
            $this->mount($this->soldPolicy->id);
            $this->alert('success' , 'info updated!');
        }else{
            $this->alert('failed','server error!');
        }

    }

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
        $this->insured_value = $this->soldPolicy->insured_value;
        $this->net_rate = $this->soldPolicy->net_rate;
        $this->net_premium = $this->soldPolicy->net_premium;
        $this->gross_premium = $this->soldPolicy->gross_premium;
        $this->installements_count = $this->soldPolicy->installements_count;
        $this->payment_frequency = $this->soldPolicy->payment_frequency;
    }

    public function render()
    {
        $BENEFITS = PolicyBenefit::BENEFITS;
        $PAYMENT_FREQS = OfferOption::PAYMENT_FREQS;
        $COLUMNS = TaskAction::COLUMNS[TaskAction::TABLE_SOLD_POLICY];
        return view('livewire.sold-policy-show', [
            'BENEFITS' => $BENEFITS,
            'PAYMENT_FREQS' => $PAYMENT_FREQS,
            'COLUMNS' => $COLUMNS
        ]);
    }
}
