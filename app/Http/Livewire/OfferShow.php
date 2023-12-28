<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Offers\Offer;
use App\Models\Cars\Car;
use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyCondition;
use App\Models\Offers\OfferOption;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;

class OfferShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire;

    public $offer;
    public $preview;
    public $clientCars;

    public $editItemSection = false;
    public $item_value;
    public $itemId;
    public $item_title;
    public $item_desc;
    public $carId;

    public $newComment;

    public $editDueSection;
    public $dueDate;
    public $dueTime;

    public $addOptionSection;
    public $searchPolicy; //search client policies
    public $policiesData; // client policies search result 
    public $policyId; //seected policy id
    public $policyData; //selected policy data
    public $policyConditions; //conditions of selected policy
    public $conditionId; // selected condtion id
    public $conditionData; // selected condtion data
    public $insured_value;
    public $payment_frequency;

    public $addFieldSection_id;
    public $newFieldName;
    public $newFieldValue;

    public function closeAddField()
    {
        $this->addFieldSection_id = null;
        $this->newFieldName = null;
        $this->newFieldValue = null;
    }

    public function openAddFieldSec($id)
    {
        $this->addFieldSection_id = $id;
    }

    public function addField()
    {
        $this->validate([
            'newFieldName' => 'required|string|max:255',
            'newFieldValue' => 'required|string|max:255'
        ]);

        $option = OfferOption::find($this->addFieldSection_id);
        $res = $option->addField($this->newFieldName, $this->newFieldValue);
        if ($res) {
            $this->alert('success', 'Field added');
            $this->closeAddField();
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function toggleEditDue()
    {
        $this->toggle($this->editDueSection);
    }

    public function editDue()
    {
        $dueDateTime = $this->dueDate . ' ' . $this->dueTime;
        $res  = $this->offer->changeDue(Carbon::parse($dueDateTime));
        if ($res) {
            $this->alert('success', 'Due Updated');
            $this->toggleEditDue();
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function toggleEditItem()
    {
        $this->toggle($this->editItemSection);
    }

    public function editItem()
    {
        $this->validate([
            'item_value' => 'nullable|numeric',
            'item_title' => 'nullable|string|max:255',
            'item_desc' => 'nullable|string',
        ]);

        $item = Car::find($this->carId);

        $res = $this->offer->setItemDetails(
            $this->item_value,
            $item,
            $this->item_title,
            $this->item_desc
        );

        if ($res) {
            $this->alert('success', 'Item updated');
            $this->toggleEditItem();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function toggleAddOption()
    {
        $this->toggle($this->addOptionSection);
    }

    public function updatedSearchPolicy()
    {
        $this->policiesData = Policy::tableData()->SearchBy($this->searchPolicy)->get()->take(5);
    }

    public function selectPolicy($id)
    {
        $this->policyId = $id;
        $this->policyData =  Policy::find($id);
        $this->policyConditions =  Policy::find($id)->conditions;
    }

    public function selectCondition($id)
    {
        $this->conditionId = $id;
        $this->conditionData =  PolicyCondition::find($this->conditionId);
        // dd($this->conditionData);
    }

    public function addOption()
    {
        $this->validate([
            'policyId' => 'required|integer|exists:policies,id',
            'conditionId' => 'required|integer|exists:policy_conditions,id',
            'insured_value' => 'nullable|numeric',
            'payment_frequency' =>  'nullable|in:' . implode(',', OfferOption::PAYMENT_FREQS),
        ], attributes: [
            'conditionId' => 'Policy'
        ]);

        $res = $this->offer->addOption(
            $this->policyId,
            $this->conditionId,
            $this->insured_value,
            $this->payment_frequency
        );

        if ($res) {
            $this->alert('success', 'options created');
            $this->searchPolicy = null;
            $this->policiesData = null;
            $this->policyId = null;
            $this->policyData = null;
            $this->policyConditions = null;
            $this->conditionId = null;
            $this->conditionData = null;
            $this->insured_value = null;
            $this->payment_frequency = null;
            $this->toggleAddOption();
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:255'
        ]);
        $res = $this->offer->addComment($this->newComment);
        if ($res) {
            $this->alert('success', $res);
            $this->mount($this->offer->id);
            $this->newComment = null;
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function mount($offerId)
    {
        $this->offer = Offer::find($offerId);
        $this->item_value = $this->offer->item_value;
        $this->item_title = $this->offer->item_title;
        $this->item_desc  = $this->offer->item_desc;

        $this->dueDate =  Carbon::parse($this->offer->due)->toDateString();
        $this->dueTime = Carbon::parse($this->offer->due)->toTimeString();
    }

    public function setStatus($s)
    {
        $res = $this->offer->setStatus($s);
        if ($res) {
            $this->alert('success', $res);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function render()
    {
        $STATUSES = Offer::STATUSES;
        $PAYMENT_FREQS = OfferOption::PAYMENT_FREQS;
        return view('livewire.offer-show', [
            'STATUSES' => $STATUSES,
            'PAYMENT_FREQS' => $PAYMENT_FREQS
        ]);
    }
}
