<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Offers\Offer;
use App\Models\Offers\OfferDiscount;
use App\Models\Cars\Car;
use App\Models\Users\User;
use App\Models\Customers\Car as CustomerCar;
use App\Models\Insurance\Policy;
use App\Models\Tasks\Task;
use App\Models\Insurance\PolicyCondition;
use App\Models\Offers\OfferDoc;
use App\Models\Offers\OfferOption;
use App\Models\Offers\OptionDoc;
use App\Models\Offers\OptionField;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use App\Models\Cars\Brand;
use App\Models\Cars\CarModel;
use App\Models\Cars\CarPrice;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\models\Business\SoldPolicy;

class OfferShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire, WithFileUploads;

    public $available_pols;
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

    public $editNoteSec = false;
    public $offerNote;
    public $inFavorTo;

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
    public $grossPremium;
    public $netPremium;
    public $optionIsRenewal = false;
    public $installmentsCount;
    public $fields = [];
    public $files = [];

    //discounts
    public $addDiscountSec = false;
    public $discountType;
    public $discountValue;
    public $discountNote;
    public $deleteDiscountId;
    public $discountId;

    public $editOptionId;

    public $addFieldSection_id;
    public $newFieldName;
    public $newFieldValue;

    public $uploadedFile;

    public $uploadedOptionFile;
    public $optionId;

    public $editInfoSection = false;

    public $deleteOptionId;

    public $deleteThisOffer = false;

    public $editAssigneeSec = false;
    public $newAsignee;

    public $upoadfiler;
    public $selectedOptions = [];

    public $whatsappMsgSec = false;
    public $whatsappMsgPhone;
    public $emailMsgSec = false;
    public $emailMsgEmail;
    public $otherEmail;
    public $otherPhone;

    public $genarteSoldPolicySection = false;
    public $policy_number;
    public $sold_insured_value;
    public $net_rate;
    public $net_premium;
    public $gross_premium;
    public $installments_count;
    public $sold_payment_frequency;
    public $start;
    public $expiry;
    public $car_chassis = null;
    public $car_plate_no = null;
    public $car_engine = null;
    public $soldInFavorTo;
    public $policyDoc;

    public $carBrand;
    public $models;
    public $carModel;
    public $CarCategory;
    public $cars;
    public $CarPrices;
    public $carPrice;
    public $selectedCarPriceArray;
    public $item;

    //watchers

    public function openGenerateSoldPolicy()
    {
        $this->genarteSoldPolicySection  = true;
        $option = OfferOption::find($this->offer->selected_option_id);
        $this->sold_insured_value = $option->insured_value;
        $this->net_rate = $option->policy_condition->rate;
        $this->net_premium = $option->net_premium;
        $this->gross_premium = $option->gross_premium;
        $this->installments_count = $option->installements_count;
        $this->sold_payment_frequency = $option->payment_frequency;
        $this->start = date('Y-m-d');
        $this->start = (string) $this->start;
        $this->expiry =  (new Carbon("+1 year"))->subDay()->format("Y-m-d");
    }

    public function closeGenerateSoldPolicy()
    {
        $this->genarteSoldPolicySection  = false;
    }


    public function generateSoldPolicy()
    {
        $this->validate([
            'policy_number' => 'required|string|max:255',
            'sold_insured_value' => 'required|numeric',
            'net_rate' => 'required|numeric',
            'net_premium' => 'required|numeric',
            'gross_premium' => 'required|numeric',
            'installments_count' => 'required|numeric',
            'sold_payment_frequency' => 'nullable|in:' . implode(',', OfferOption::PAYMENT_FREQS),
            'start' => 'required|date',
            'expiry' => 'required|date|after:start',
            'car_chassis' => 'nullable|string|max:255',
            'car_plate_no' => 'nullable|string|max:255',
            'car_engine' => 'nullable|string|max:255',
            'soldInFavorTo' =>  'nullable|string|max:255',
            'policyDoc' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
        ]);

        if ($this->policyDoc) {
            $url = $this->policyDoc->store(SoldPolicy::FILES_DIRECTORY, 's3');
        } else {
            $url = null;
        }


        $res = $this->offer->generateSoldPolicy(
            $this->policy_number,
            $url,
            Carbon::parse($this->start),
            Carbon::parse($this->expiry),
            $this->sold_insured_value,
            $this->net_rate,
            $this->net_premium,
            $this->gross_premium,
            $this->installments_count,
            $this->sold_payment_frequency,
            $this->car_chassis,
            $this->car_engine,
            $this->car_plate_no,
            $this->soldInFavorTo

        );
        if ($res) {
            $this->reset();
            return redirect(route('sold.policy.show', $res->id));
            // $this->alert('success', 'Sold Policy added');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function toggleWhatsappSection()
    {
        $this->toggle($this->whatsappMsgSec);
    }

    public function toggleEmailMsgSection()
    {
        $this->toggle($this->emailMsgSec);
    }

    public function submitWhastappMsg()
    {
        if ($this->whatsappMsgPhone === 'other') {
            $this->validate([
                'otherPhone' => 'required|string|max:255'
            ]);
            $phone = $this->otherPhone;
        } else {
            $this->validate([
                'whatsappMsgPhone' => 'required|string|max:255'
            ]);
            $phone = $this->whatsappMsgPhone;
        }

        $res = $this->offer->generateWhatsappUrl($phone, $this->selectedOptions);
        if ($res) {
            // return $this->redirect($res); //we need redirect to a new tab
            $this->alert('success', 'Message Sent');
            $this->whatsappMsgPhone = null;
            $this->whatsappMsgSec = false;
            $this->dispatchBrowserEvent('openNewTab', ['url' => $res]);
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function submitEmailMsg()
    {

        if ($this->emailMsgEmail === 'other') {
            $this->validate([
                'otherEmail' => 'required|email'
            ]);
            $email = $this->otherEmail;
        } else {
            $this->validate([
                'emailMsgEmail' => 'required|email'
            ]);
            $email = $this->emailMsgEmail;
        }

        $res = $this->offer->generateEmailUrl($email, $this->selectedOptions);

        if ($res) {
            $this->alert('success', 'Message Sent');
            $this->emailMsgEmail = null;
            $this->emailMsgSec = false;
            $this->dispatchBrowserEvent('openNewTab', ['url' => $res]);
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function exportComparison()
    {
        return $this->offer->exportComparison($this->selectedOptions);
    }

    public function UpdatedUpoadfiler()
    {
        dd($this->upoadfiler);
    }

    public function generateWhatsappMsg()
    {
        if ($this->whatsappMsgPhone === 'other') {
            $phone = $this->otherPhone;
        } else {
            $phone = $this->whatsappMsgPhone;
        }

        $res = $this->offer->generateWhatsappUrl($phone, $this->selectedOptions);
        return '<script>window.open("' . $res . '", "_blank");</script>';
    }

    public function changeAsignee()
    {
        $res = $this->offer->assignTo($this->newAsignee);
        if ($res) {
            $this->alert('success', 'Assignee Updated');
            $this->newAsignee = null;
            $this->toggleEditAssignee();
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function toggleAddDiscount()
    {
        $this->toggle($this->addDiscountSec);
    }

    public function editThisDicount($id)
    {
        $this->discountId = $id;
        $discount = OfferDiscount::find($id);
        $this->discountType = $discount->type;
        $this->discountValue = $discount->value;
        $this->discountNote = $discount->note;
    }

    public function deleteThisDiscount($id)
    {
        $this->deleteDiscountId = $id;
    }

    public function closeEditDiscount()
    {
        $this->discountId = null;
    }

    public function deleteDiscount()
    {
        $res = OfferDiscount::find($this->deleteDiscountId)->delete();
        if ($res) {
            $this->deleteDiscountId = null;
            $this->alert('success', 'Discount Deleted!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function dismissDeleteDiscount()
    {
        $this->deleteDiscountId = null;
    }

    public function addDiscount()
    {
        $this->validate([
            'discountType' => 'required|in:' . implode(',', OfferDiscount::TYPES),
            'discountValue' => 'required|numeric',
            'discountNote' => 'nullable|string|max:255',
        ]);
        $res = $this->offer->addDiscount($this->discountType, $this->discountValue, $this->discountNote);
        if ($res) {
            $this->discountType = null;
            $this->discountValue = null;
            $this->discountNote = null;
            $this->toggleAddDiscount();
            $this->mount($this->offer->id);
            $this->alert('success', 'Discount Added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function updateDiscount()
    {
        $this->validate([
            'discountType' => 'required|in:' . implode(',', OfferDiscount::TYPES),
            'discountValue' => 'required|numeric',
            'discountNote' => 'nullable|string|max:255',
        ]);

        $res = OfferDiscount::find($this->discountId)->editInfo($this->discountType, $this->discountValue, $this->discountNote);
        if ($res) {
            $this->discountType = null;
            $this->discountValue = null;
            $this->discountNote = null;
            $this->closeEditDiscount();
            $this->mount($this->offer->id);
            $this->alert('success', 'Discount Added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function toggleEditAssignee()
    {
        $this->toggle($this->editAssigneeSec);
    }

    public function addAnotherField()
    {
        $this->fields[] = ['field' => '', 'value' => ''];
    }

    public function removeField($index)
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    public function confirmDeleteOffer()
    {
        $this->deleteThisOffer = true;
    }
    public function dismissDeleteOffer()
    {
        $this->deleteThisOffer = false;
    }

    public function deleteOffer()
    {
        $res = Offer::find($this->offer->id)->delete();
        if ($res) {
            return redirect(route('offers.index'));
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function deleteThisOption($id)
    {
        $this->deleteOptionId = $id;
    }

    public function dismissDeleteOption()
    {
        $this->deleteOptionId = null;
    }

    public function deleteOption()
    {
        $res = OfferOption::find($this->deleteOptionId)->delete();
        if ($res) {
            $this->alert('success', 'Option deleted');
            $this->dismissDeleteOption();
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function editThisOption($id)
    {
        $this->editOptionId = $id;
        $option = OfferOption::find($id);

        // dd($option);
        $this->policyData = $option->policy;
        $this->conditionData = $option->policy_condition;
        $this->insured_value = $option->insured_value;
        $this->payment_frequency = $option->payment_frequency;
        $this->grossPremium = $option->gross_premium;
        $this->netPremium = $option->net_premium;
        $this->optionIsRenewal = $option->is_renewal;
        $this->installmentsCount = $option->installements_count;
    }

    public function closeEditOption()
    {
        $this->editOptionId = null;
        $this->policyData = null;
        $this->conditionData = null;
        $this->insured_value = null;
        $this->payment_frequency = null;
    }

    public function editOption()
    {
        $option = OfferOption::find($this->editOptionId);
        $res = $option->editInfo($this->insured_value, $this->netPremium, $this->grossPremium, $this->payment_frequency, $this->optionIsRenewal, $this->installmentsCount);
        if ($res) {
            $this->alert('success', 'Option updated');
            $this->closeEditOption();
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function clearPolicy()
    {
        $this->policyId = null;
        $this->conditionId = null;
        $this->searchPolicy = null;
        $this->policiesData = null;
        $this->policyData = null;
        $this->conditionData = null;
        $this->policyConditions = null;
    }
    public function toggleEditInfo()
    {
        $this->toggle($this->editInfoSection);
    }

    public function uploadDocOptionId($id)
    {
        $this->optionId = $id;
    }

    public function downloadOptionDoc($id)
    {
        $doc = OptionDoc::findOrFail($id);
        $fileContents = Storage::disk('s3')->get($doc->url);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $doc->name . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function deleteOptionDoc($id)
    {
        $res = OptionDoc::find($id)->delete();
        if ($res) {
            $this->alert('success', 'Document deleted!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function deleteOptionField($id)
    {
        $res = OptionField::find($id)->delete();
        if ($res) {
            $this->alert('success', 'Field deleted!');
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function removeOptionFile($id)
    {
        $res = OptionDoc::find($id)->delete();
        if ($res) {
            $this->alert('success', 'File Deleted');
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function removeOfferFile($id)
    {
        $res = OfferDoc::find($id)->delete();
        if ($res) {
            $this->alert('success', 'File Deleted');
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function downloadOfferFile($id)
    {
        $doc = OfferDoc::findOrFail($id);

        // $extension = pathinfo($task->name, PATHINFO_EXTENSION);
        $fileContents = Storage::disk('s3')->get($doc->url);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $doc->name . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    // upload file for option
    public function UpdatedUploadedOptionFile()
    {
        $this->validate(
            [
                'uploadedOptionFile' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
            ],
            [
                'uploadedOptionFile.max' => 'The file must not be greater than 5MB.',
            ],
        );

        $filename = $this->uploadedOptionFile->getClientOriginalName();
        $url = $this->uploadedOptionFile->store(OptionDoc::FILES_DIRECTORY, 's3');
        $option = OfferOption::find($this->optionId);
        $o = $option->addFile($filename, $url);
        if ($o) {
            $this->alert('success', 'File Uploaded!');
            $this->optionId = null;
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

    // upload file for the offer
    public function UpdatedUploadedFile()
    {
        $this->validate(
            [
                'uploadedFile.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
            ],
            [
                'uploadedFile.*.max' => 'The file must not be greater than 5MB.',
            ],
        );

        foreach ($this->uploadedFile as $file) {
            $filename = $file->getClientOriginalName();
            $url = $file->store(OfferDoc::FILES_DIRECTORY, 's3');
            $o = $this->offer->addFile($filename, $url);
        }

        if ($o) {
            $this->alert('success', 'File Uploaded!');
            $this->uploadedFile = null;
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

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
            'newFieldValue' => 'required|string|max:255',
        ]);

        $option = OfferOption::find($this->addFieldSection_id);
        $res = $option->addField($this->newFieldName, $this->newFieldValue);
        if ($res) {
            $this->alert('success', 'Field added');
            $this->closeAddField();
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function toggleEditDue()
    {
        $this->toggle($this->editDueSection);
    }

    public function toggleEditNote()
    {
        $this->toggle($this->editNoteSec);
        $this->offerNote = $this->offer->note;
        $this->inFavorTo = $this->offer->in_favor_to;
    }

    public function editNote()
    {
        $this->validate([
            'inFavorTo'     => 'nullable|string|max:255',
            'offerNote'     => 'nullable|string|max:255'
        ]);

        $res = $this->offer->setNote($this->inFavorTo, $this->offerNote);
        if ($res) {
            $this->alert('success', 'Note Changed!');
            $this->toggleEditNote();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editDue()
    {
        $dueDateTime = $this->dueDate . ' ' . $this->dueTime;
        $res = $this->offer->changeDue(Carbon::parse($dueDateTime));
        if ($res) {
            $this->alert('success', 'Due Updated');
            $this->toggleEditDue();
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function toggleEditItem()
    {
        $this->toggle($this->editItemSection);
        if ($this->editItemSection) {
            $this->carId = $this->offer->item_id;
        }
    }

    public function editItem()
    {
        $this->validate([
            'item_value' => 'nullable|numeric',
            'item_title' => 'nullable|string|max:255',
            'item_desc' => 'nullable|string',
        ]);
        
        if ($this->carId) {
            $item = CustomerCar::find($this->carId);
        } elseif ($this->selectedCarPriceArray && $this->CarCategory) {
            $item = $this->offer->client->addCar(car_id: $this->CarCategory, model_year: $this->selectedCarPriceArray['model_year']);
        }


        $res = $this->offer->setItemDetails($this->item_value, $item, $this->item_title, $this->item_desc);

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
        $this->policiesData = Policy::tableData()
            ->SearchBy($this->searchPolicy)
            ->get()
            ->take(5);
    }

    public function selectPolicy($id)
    {
        $this->policyId = $id;
        $this->policyData = Policy::find($id);
        $this->policyConditions = Policy::find($id)->conditions;
    }

    public function selectCondition($id)
    {
        $this->conditionId = $id;
        $this->conditionData = PolicyCondition::find($this->conditionId);
        $this->insured_value = $this->offer->item_value;

        if ($this->conditionData) {
            $this->netPremium = round($this->offer->item_value * ($this->conditionData->rate / 100), 2);
        }
        if ($this->policyData && $this->netPremium) {
            $this->grossPremium = round($this->policyData->calculateGrossValue($this->netPremium), 2);
        }
        // dd($this->conditionData);
    }

    public function addOption()
    {
        $this->validate(
            [
                'policyId' => 'required|integer|exists:policies,id',
                'conditionId' => 'required|integer|exists:policy_conditions,id',
                'insured_value' => 'nullable|numeric',
                'payment_frequency' => 'nullable|in:' . implode(',', OfferOption::PAYMENT_FREQS),
                'grossPremium' => 'nullable|numeric',
                'netPremium' => 'nullable|numeric',
                'installmentsCount' => 'nullable|numeric',
                'optionIsRenewal' => 'boolean',
                'files' => 'nullable|array',
                'files.*' => 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
                'fields' => 'nullable|array',
            ],
            messages: [
                'conditionId' => 'Policy is required!',
                'files.*' => 'File :position is invalid. Please upload a file of type: pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp',
            ],
        );

        $validationRules = [];

        if (!empty($this->fields)) {
            foreach ($this->fields as $index => $field) {
                $validationRules["fields.$index.field"] = 'required|string|max:255';
                $validationRules["fields.$index.value"] = 'required|numeric';
            }
            $this->validate($validationRules);
        }
        $files = [];
        if (!empty($this->files)) {
            foreach ($this->files as $i => $file) {
                $files[$i] = ['name' => $file->getClientOriginalName(), 'url' => $file->store(OptionDoc::FILES_DIRECTORY, 's3')];
            }
        }
        $res = $this->offer->addOption($this->policyId, $this->conditionId, $this->insured_value, $this->payment_frequency, $this->netPremium, $this->grossPremium, $this->optionIsRenewal, $this->installmentsCount, $this->fields, $files);

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
            $this->fields = [];
            $this->files = [];
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function generateOption($policyId, $conditionId)
    {
        $this->toggleAddOption();
        $this->selectPolicy($policyId);
        $this->selectCondition($conditionId);
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:255',
        ]);
        $res = $this->offer->addComment($this->newComment);
        if ($res) {
            $this->alert('success', 'comment added!');
            $this->mount($this->offer->id, []);
            $this->newComment = null;
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setIsRenewal()
    {
        $res = $this->offer->setRenewalFlag(true);
        if ($res) {
            $this->alert('success', 'Renewal Status Changed!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function removeRenewal()
    {
        $res = $this->offer->setRenewalFlag(false);
        if ($res) {
            $this->alert('success', 'Renewal removed!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function mount($offerId)
    {
        $this->offer = Offer::find($offerId);
        $this->item_value = $this->offer->item_value;
        $this->item_title = $this->offer->item_title;
        $this->item_desc = $this->offer->item_desc;

        $this->dueDate = Carbon::parse($this->offer->due)->toDateString();
        $this->dueTime = Carbon::parse($this->offer->due)->toTimeString();

        //watchers code
        $this->watchersList = $this->offer->watcher_ids;
    }

    public function setStatus($s)
    {
        $res = $this->offer->setStatus($s);
        if ($res) {
            $this->alert('info', $res);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function changeOptionState($optionId, $status)
    {
        $res = $this->offer->setOptionState($optionId, $status);
        if ($res) {
            $this->mount($this->offer->id);
            $this->alert('info', $res);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function updatedCarBrand($value)
    {
        $this->models = CarModel::where('brand_id', $value)->get();
        if ($value === '') {
            $this->carModel = null;
            $this->CarCategory = null;
        }
        $this->CarCategory = null;
    }

    public function updatedCarModel($value)
    {
        $this->cars = Car::where('car_model_id', $value)->get();
        $this->CarCategory = null;
    }

    public function updatedCarCategory()
    {
        if ($this->CarCategory) {
            $this->CarPrices = CarPrice::where('car_id', $this->CarCategory)->get();
        }
    }
    public function updatedCarPrice()
    {
        if ($this->carPrice) {
            $this->selectedCarPriceArray = (array) json_decode($this->carPrice);
            $this->item_value = $this->selectedCarPriceArray['price'];
        }
    }

    public function updatedItem()
    {
        if ($this->item) {
            $this->CarPrices = CarPrice::where('car_id', $this->item)->get();
        }
    }

    /////watchers sections
    public $changeWatchers = false;
    public $watchersList = [];
    public $setWatchersList;


    public function OpenChangeWatchers()
    {
        $this->changeWatchers = true;
    }
    public function closeChangeWatchers()
    {
        $this->changeWatchers = false;
    }
    public function saveWatchers()
    {
        $this->validate([
            'setWatchersList' => 'nullable|array',
            'setWatchersList.*' => 'integer|exists:users,id',
        ], [], [
            'setWatchersList' => 'Watchers',
        ]);

        $t = $this->offer->setWatchers($this->setWatchersList);
        if ($t) {
            $this->alert('success', 'Watchers Updated!');
            $this->closeChangeWatchers();
            $this->mount($this->offer->id);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

    public function render()
    {
        $users = User::all();
        $usersTypes = User::TYPES;
        $STATUSES = Offer::STATUSES;
        $PAYMENT_FREQS = OfferOption::PAYMENT_FREQS;
        $DISCOUNT_TYPES = OfferDiscount::TYPES;
        $optionStatuses = OfferOption::STATUSES;
        $brands = Brand::all();
        if ($this->offer->item)
            $this->available_pols = Policy::getAvailablePolicies(type: $this->offer->type, car: $this->offer->item, age: null, offerValue: $this->offer->item_value);

        return view('livewire.offer-show', [
            'STATUSES' => $STATUSES,
            'PAYMENT_FREQS' => $PAYMENT_FREQS,
            'users' => $users,
            'usersTypes' => $usersTypes,
            'DISCOUNT_TYPES' => $DISCOUNT_TYPES,
            'optionStatuses' => $optionStatuses,
            'brands' => $brands
        ]);
    }
}
