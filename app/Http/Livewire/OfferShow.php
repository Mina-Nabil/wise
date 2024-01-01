<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Offers\Offer;
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
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class OfferShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire, WithFileUploads;

    public $AVAILABLE_POLICIES;
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
    public $fields = [];
    public $files = [];

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
    public $asigneeType = 'user';
    public $newAsignee;

    public function changeAsignee(){
        $res = $this->offer->assignTo($this->newAsignee);
        if ($res) {
            $this->alert('success' , 'Assignee Updated');
            $this->asigneeType = 'user';
            $this->newAsignee = null;
            $this->toggleEditAssignee();
            $this->mount($this->offer->id);
        }else{
            $this->alert('failed' , 'Server Error');
        }
    }

    public function toggleEditAssignee() {
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
        $this->editOptionId  =  $id;
        $option = OfferOption::find($id);

        // dd($option);
        $this->policyData = $option->policy;
        $this->conditionData = $option->policy_condition;
        $this->insured_value = $option->insured_value;
        $this->payment_frequency = $option->payment_frequency;
    }

    public function closeEditOption()
    {
        $this->editOptionId = null;
        $this->policyData =  null;
        $this->conditionData =  null;
        $this->insured_value = null;
        $this->payment_frequency =  null;
    }

    public function editOption()
    {
        $option = OfferOption::find($this->editOptionId);
        $res = $option->editInfo($this->insured_value, $this->payment_frequency);
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
                'uploadedFile' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
            ],
            [
                'uploadedFile.max' => 'The file must not be greater than 5MB.',
            ],
        );

        $filename = $this->uploadedFile->getClientOriginalName();
        $url = $this->uploadedFile->store(OfferDoc::FILES_DIRECTORY, 's3');
        $o = $this->offer->addFile($filename, $url);
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
            'newFieldValue' => 'required|string|max:255'
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

    public function editDue()
    {
        $dueDateTime = $this->dueDate . ' ' . $this->dueTime;
        $res  = $this->offer->changeDue(Carbon::parse($dueDateTime));
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
            'files' => 'nullable|array',
            'files.*' => 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
            'fields' => 'nullable|array',
        ], messages: [
            'conditionId' => 'Policy is required!'
        ]);

        $validationRules = [];



        if (!empty($this->fields)) {
            foreach ($this->fields as $index => $field) {
                $validationRules["fields.$index.field"] = 'required|string|max:255';
                $validationRules["fields.$index.value"] = 'required|numeric';
            }
            $this->validate($validationRules);
        }

        $res = $this->offer->addOption(
            $this->policyId,
            $this->conditionId,
            $this->insured_value,
            $this->payment_frequency
        );

        if (!empty($this->fields)) {
            foreach ($this->fields as $field) {
                $res->addField($field['field'], $field['value']);
            }
        }

        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $filename = $file->getClientOriginalName();
                $url = $file->store(OptionDoc::FILES_DIRECTORY, 's3');
                $res->addFile($filename, $url);
            }
        }


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



        $item = $this->offer->item;
        $this->AVAILABLE_POLICIES = Policy::getAvailablePolicies(Policy::BUSINESS_PERSONAL_MOTOR,$item,null);   
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
        $users = User::all();
        $usersTypes = User::TYPES;
        $STATUSES = Offer::STATUSES;
        $PAYMENT_FREQS = OfferOption::PAYMENT_FREQS;
        

        return view('livewire.offer-show', [
            'STATUSES' => $STATUSES,
            'PAYMENT_FREQS' => $PAYMENT_FREQS,
            'users' => $users,
            'usersTypes' => $usersTypes,
        ]);
    }
}
