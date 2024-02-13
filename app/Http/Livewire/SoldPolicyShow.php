<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Business\SoldPolicy;
use App\Models\Business\SoldPolicyBenefit;
use App\Models\Business\SoldPolicyExclusion;
use App\Models\Insurance\PolicyBenefit;
use App\Models\Offers\OfferOption;
use App\Models\Tasks\TaskAction;
use App\Models\Tasks\TaskField;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use PhpParser\Node\Expr\FuncCall;

class SoldPolicyShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire, WithFileUploads;

    public $soldPolicy;
    public $start;
    public $expiry;
    public $policy_number;
    public $car_chassis;
    public $car_plate_no;
    public $car_engine;
    public $in_favor_to;
    public $editInfoSec = false;

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
    public $discount;

    public $actions = [];
    public $fields = [];
    public $newTaskType = 'claim';
    public $newTaskDesc;
    public $newTaskDue;
    public $newTaskSection = false;
    public $newClaimSection = false;
    public $newEndorsementSection = false;
    public $deleteDocSec = false;
    public $docFile;

    public $generateRenewalOfferSec = false;
    public $renewalOfferDue;

    public function toggleGenerateRenewalOfferSec(){
        $this->toggle($this->generateRenewalOfferSec);
    }

    public function generateRenewalOffer(){
        $res = $this->soldPolicy->generateRenewalOffer(Carbon::parse($this->renewalOfferDue));
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->toggleGenerateRenewalOfferSec();
            $this->alert('success', 'document deleted');
            $this->dispatchBrowserEvent('openNewTab', ['url' => '/offers'.'/'.$res->id]);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function toggleDeleteDoc()
    {
        $this->toggle($this->deleteDocSec);
    }

    public function deleteDucment()
    {
        $res = $this->soldPolicy->deletePolicyDoc();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->toggleDeleteDoc();
            $this->alert('success', 'document deleted');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function updatedDocFile()
    {
        $this->validate([
            'docFile' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
        ]);

        $url = $this->docFile->store(SoldPolicy::FILES_DIRECTORY, 's3');

        $res = $this->soldPolicy->setPolicyDoc($url);
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'document added');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function downloadDoc()
    {
        $fileContents = Storage::disk('s3')->get($this->soldPolicy->policy_doc);
        $extension = pathinfo($this->soldPolicy->policy_doc, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $this->soldPolicy->policy_number . '_document.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function setInvalid()
    {
        $res = $this->soldPolicy->setAsInvalid();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'status updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setValid()
    {
        $res = $this->soldPolicy->setAsValid();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'status updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }


    public function openEditInfoSection()
    {
        $this->editInfoSec = true;
        $this->start = Carbon::parse($this->soldPolicy->start)->toDateString();
        $this->expiry = Carbon::parse($this->soldPolicy->expiry)->toDateString();
        $this->policy_number = $this->soldPolicy->policy_number;
        $this->car_chassis = $this->soldPolicy->car_chassis;
        $this->car_plate_no = $this->soldPolicy->car_plate_no;
        $this->car_engine = $this->soldPolicy->car_engine;
        $this->in_favor_to = $this->soldPolicy->in_favor_to;
    }

    public function editInfo()
    {
        $this->validate([
            'start' => 'required|date',
            'expiry' => 'required|date',
            'policy_number' => 'required|string|max:255',
            'car_chassis' => 'nullable|string|max:255',
            'car_plate_no' => 'nullable|string|max:255',
            'car_engine' => 'nullable|string|max:255',
            'in_favor_to' => 'nullable|string|max:255',
        ]);

        $res = $this->soldPolicy->editInfo(
            Carbon::parse($this->start),
            Carbon::parse($this->expiry),
            $this->policy_number,
            $this->car_chassis,
            $this->car_plate_no,
            $this->car_engine,
            $this->in_favor_to
        );

        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->closeEditInfoSection();
            $this->alert('success', 'Policy updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function closeEditInfoSection()
    {
        $this->editInfoSec = false;
    }

    public function toggleNewTaskSection()
    {
        $this->toggle($this->newTaskSection);
    }

    public function closeNewTaskSection()
    {
        $this->toggle($this->newTaskSection);
    }

    public function toggleNewClaimSection()
    {
        $this->toggle($this->newClaimSection);
    }

    public function closeNewClaimSection()
    {
        $this->toggle($this->newClaimSection);
        $this->newTaskDesc = null;
        $this->newTaskDue = null;
        $this->actions[] = [];
        $this->actions[] = ['column_name' => '', 'value' => ''];
    }

    public function toggleNewEndorsementSection()
    {
        $this->toggle($this->newEndorsementSection);
    }

    public function closeNewEndorsementSection()
    {
        $this->newEndorsementSection = false;
        $this->newTaskDesc = null;
        $this->newTaskDue = null;
        $this->actions[] = [];
        $this->actions[] = ['column_name' => '', 'value' => ''];
    }

    public function createClaim()
    {
        $this->validate([
            'newTaskDesc' => 'nullable|string',
            'newTaskDue' => 'nullable|date',
            'fields.*.title' => 'required|string|max:255',
            'fields.*.value' => 'required|string|max:255',
        ]);

        $res = $this->soldPolicy->addClaim(Carbon::parse($this->newTaskDue), $this->newTaskDesc, $this->fields);

        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->closeNewClaimSection();
            $this->alert('success', 'Claim added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function createEndorsement()
    {
        $this->validate([
            'newTaskDesc' => 'nullable|string',
            'newTaskDue' => 'nullable|date',
            'actions.*.column_name' => 'required|string|max:255',
            'actions.*.value' => 'required|string|max:255',
        ]);

        $res = $this->soldPolicy->addEndorsement(Carbon::parse($this->newTaskDue), $this->newTaskDesc, $this->actions);

        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->closeNewEndorsementSection();
            $this->alert('success', 'Endorsement added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }


    public function removeAcion($index)
    {
        if (count($this->actions) > 1) {
            unset($this->actions[$index]);
            $this->actions = array_values($this->actions);
        }
    }

    public function addAnotherAction()
    {
        $this->actions[] = ['column_name' => '', 'value' => ''];
    }

    public function removeField($index)
    {
        if (count($this->fields) > 1) {
            unset($this->fields[$index]);
            $this->fields = array_values($this->fields);
        }
    }

    public function addAnotherField()
    {
        $this->fields[] = ['title' => '', 'value' => ''];
    }

    //paymentInfo
    public function togglePaymentInfoSection()
    {
        $this->toggle($this->editPaymentInfoSection);
    }

    public function editPaymentInfo()
    {
        $this->validate([
            'insured_value' => 'required|numeric',
            'net_rate' => 'required|numeric',
            'net_premium' => 'required|numeric',
            'gross_premium' => 'required|numeric',
            'installements_count' => 'required|numeric',
            'payment_frequency' => 'nullable|in:' . implode(',', OfferOption::PAYMENT_FREQS),
            'discount' => 'required|numeric',
        ]);

        $res = $this->soldPolicy->updatePaymentInfo(
            $this->insured_value,
            $this->net_rate,
            $this->net_premium,
            $this->gross_premium,
            $this->installements_count,
            $this->payment_frequency,
            $this->discount
        );

        if ($res) {
            $this->togglePaymentInfoSection();
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'info updated!');
        } else {
            $this->alert('failed', 'server error!');
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
        $this->discount = $this->soldPolicy->discount;
        $this->actions[] = ['column_name' => '', 'value' => ''];
        $this->fields[] = ['title' => '', 'value' => ''];
    }

    public function render()
    {
        $BENEFITS = PolicyBenefit::BENEFITS;
        $PAYMENT_FREQS = OfferOption::PAYMENT_FREQS;
        $COLUMNS = TaskAction::COLUMNS[TaskAction::TABLE_SOLD_POLICY];
        $FIELDSTITLES = TaskField::TITLES;
        return view('livewire.sold-policy-show', [
            'BENEFITS' => $BENEFITS,
            'PAYMENT_FREQS' => $PAYMENT_FREQS,
            'COLUMNS' => $COLUMNS,
            'FIELDSTITLES' => $FIELDSTITLES
        ]);
    }
}
