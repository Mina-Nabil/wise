<?php

namespace App\Http\Livewire;

use App\Exceptions\InvalidSoldPolicyException;
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
use App\Models\Payments\CommProfile;
use App\Models\Payments\SalesComm;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\Customers\Relative;

class OfferShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire, WithFileUploads, AuthorizesRequests;

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
    public $lineFields = [];
    public $files = [];

    //discounts
    public $addDiscountSec = false;
    public $discountType;
    public $discountValue;
    public $discountNote;
    public $deleteDiscountId;
    public $discountId;

    //sales comm
    public $addCommSec = false;
    public $commTitle;
    public $commUser;
    public $commPer;
    public $commNote;
    public $commStatus;
    public $deleteCommId;
    public $commId;

    //renewal
    public $setRenewalSec = false;
    public $searchPolicyText;
    public $searchedPolicies;
    public $selectedPolicy;

    //fields sec
    public $showOfferFieldsModal = false;


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
    public $issuing_date;
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

    public $commSearch;
    public $profilesRes;

    public $subStatusSection;
    public $subStatusOfferStatus;
    public $subStatus;

    public $relatives = [];

    public $showPolicyNoteModal = false;
    public $currentPolicyNote = '';

    public function removeRelative($index)
    {
        unset($this->relatives[$index]);
        $this->relatives = array_values($this->relatives);
    }

    public function addAnotherRelative()
    {
        $this->relatives[] = [
            'name' => '',
            'relation' => Relative::RELATION_MAIN,
            'birth_date' => ''
        ];
    }

    public function updatedCommSearch()
    {
        if (!empty($this->commSearch)) {
            // Perform the query using a scope or direct query
            $this->profilesRes = CommProfile::availableForSelection()->searchBy($this->commSearch)->take(15)->get();
        } else {
            // Return an empty collection if the search term is empty
            $this->profilesRes = collect(); // This creates an empty Laravel collection
        }
    }

    public function addCommProfile($id)
    {

        $res = $this->offer->addCommProfile($id);
        if ($res) {
            $this->addCommSec = false;
            $this->profilesRes = collect();
            $this->mount($this->offer->id);
            $this->alert('success', 'Commission added!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function removeCommProfile($id)
    {

        $res = $this->offer->removeCommProfile($id);
        if ($res) {
            $this->mount($this->offer->id);
            $this->alert('success', 'Commission removed!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function addComm()
    {
        $this->validate([
            'commTitle' => 'required|string|max:255',
            'commPer' => 'required|numeric',
            'commUser' => 'nullable|integer|exists:users,id',
            'commNote' => 'nullable|string',
        ]);

        $res = $this->offer->addSalesCommission($this->commTitle, $this->commPer, $this->commUser, $this->commNote);
        if ($res) {
            $this->toggleAddComm();
            $this->commTitle = null;
            $this->commPer = null;
            $this->commUser = null;
            $this->commNote = null;
            $this->mount($this->offer->id);
            $this->alert('success', 'Commission added!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function toggleAddComm()
    {
        $this->toggle($this->addCommSec);
    }
    public function deleteThisComm($id)
    {
        $this->deleteCommId = $id;
    }

    public function dismissDeleteComm()
    {
        $this->deleteCommId = null;
    }

    public function deleteComm()
    {
        $res = SalesComm::find($this->deleteCommId)->delete();
        if ($res) {
            $this->dismissDeleteComm();
            $this->mount($this->offer->id);
            $this->alert('success', 'Commission deleted!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function openGenerateSoldPolicy()
    {
        $option = OfferOption::find($this->offer->selected_option_id);
        if (!$option) {
            $this->alert('warning', "Please set an option as 'Client Selected'");
            return;
        }
        $this->genarteSoldPolicySection  = true;
        $this->sold_insured_value = $option->insured_value;
        $this->net_rate = $option->policy_condition?->rate;
        $this->net_premium = $option->net_premium;
        $this->gross_premium = $option->gross_premium;
        $this->installments_count = $option->installements_count;
        $this->sold_payment_frequency = $option->payment_frequency;
        $this->policy_number = $this->offer->renewal_policy;
        $this->soldInFavorTo = $this->offer->in_favor_to;
        $oldPolicy = $this->offer->renewal_sold_policy;
        if ($oldPolicy) {
            $this->car_chassis = $oldPolicy->car_chassis;
            $this->car_plate_no = $oldPolicy->car_plate_no;
            $this->car_engine = $oldPolicy->car_engine;
            $this->soldInFavorTo = $oldPolicy->in_favor_to;
        }
    }

    public function closeGenerateSoldPolicy()
    {
        $this->genarteSoldPolicySection  = false;
    }

    public function updatedStart()
    {
        $this->expiry = Carbon::parse($this->start)->addYears(1)->format('Y-m-d');
    }


    public function generateSoldPolicy()
    {
        $this->validate([
            'policy_number' => 'required|string|max:255',
            'sold_insured_value' => 'required|numeric',
            'net_rate' => 'required|numeric',
            'net_premium' => 'required|numeric',
            'gross_premium' => 'required|numeric',
            'sold_payment_frequency' => 'required|in:' . implode(',', OfferOption::PAYMENT_FREQS),
            'installments_count' => 'required_if:sold_payment_frequency,' . OfferOption::PAYMENT_INSTALLEMENTS . '|nullable|numeric',
            'issuing_date' => 'required|date',
            'start' => 'required|date|after:2020-01-01',
            'expiry' => 'required|date|after:start',
            'car_chassis' => 'nullable|string|max:255',
            'car_plate_no' => 'nullable|string|max:255',
            'car_engine' => 'nullable|string|max:255',
            'soldInFavorTo' =>  'nullable|string|max:255',
            'policyDoc' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
        ]);

        if ($this->policyDoc) {
            $url = $this->policyDoc->store(SoldPolicy::FILES_DIRECTORY, 's3');
        } else {
            $url = null;
        }

        try {

            $res = $this->offer->generateSoldPolicy(
                $this->policy_number,
                $url,
                Carbon::parse($this->start),
                Carbon::parse($this->expiry),
                $this->installments_count,
                $this->sold_payment_frequency,
                $this->sold_insured_value,
                $this->net_rate,
                $this->net_premium,
                $this->gross_premium,
                $this->car_chassis,
                $this->car_engine,
                $this->car_plate_no,
                $this->soldInFavorTo,
                Carbon::parse($this->issuing_date),

            );
            if ($res) {
                $this->reset();
                return redirect(route('sold.policy.show', $res->id));
                // $this->alert('success', 'Sold Policy added');
            } else {
                $this->alert('failed', 'server error');
            }
        } catch (InvalidSoldPolicyException $e) {
            $this->alert('failed', $e->getMessage());
        } catch (Exception $e) {
            report($e);
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
        $offer = Offer::with('renewal_sold_policy')->find($this->offer->id);
        $res = $offer->delete();
        if ($res) {
            if ($offer->renewal_sold_policy) {
                $offer->renewal_sold_policy->update(['is_renewed' => 0]);
            }
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
        $res = OfferOption::find($this->deleteOptionId)->deleteOption();

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
        if (!$option) $this->alert('failed', 'Option not found. Please refresh');
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

    public function previewOfferFile($id)
    {
        $doc = OfferDoc::findOrFail($id);
        $url = Storage::disk('s3')->url($doc->url);
        $url = str_replace('docs//', 'docs/', $url);

        $this->dispatchBrowserEvent('openNewTab', ['url' => $url]);
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
                'uploadedOptionFile' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
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
                'uploadedFile.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
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
            if ($this->offer->is_medical) {
                $this->relatives = [];
                foreach ($this->offer->medical_offer_clients as $client) {
                    $this->relatives[] = [
                        'name' => $client->name,
                        'birth_date' => $client->birth_date,
                        'relation' => $client->relation ?? Relative::RELATION_MAIN,
                    ];
                }
            } elseif ($this->offer->is_motor) {
                $this->carId = $this->offer->item_id;
            }
        }
    }

    public function editItem()
    {
        $this->validate([
            'item_value' => 'nullable|numeric',
            'item_title' => 'nullable|string|max:255',
            'item_desc' => 'nullable|string',
        ]);
        $item = null;
        if ($this->offer->is_medical) {
            $item = $this->offer->medical_offer_clients;
            $this->validate([
                'relatives.*.name' => 'required|string|max:255',
                'relatives.*.birth_date' => 'required|date',
                'relatives.*.relation' => 'required|in:' . implode(',', Relative::RELATIONS),
            ]);
            $res = $this->offer->setMedicalClients($this->relatives);
            $this->relatives = [];
            if ($res) {
                $this->alert('success', 'Item updated');
                $this->mount($this->offer->id);
                $this->toggleEditItem();
            } else {
                $this->alert('failed', 'server error');
            }
            return;
        } elseif ($this->carId) {
            $item = CustomerCar::find($this->carId);
        } elseif ($this->selectedCarPriceArray && $this->CarCategory) {
            $item = $this->offer->client->addCar(car_id: $this->CarCategory, model_year: $this->selectedCarPriceArray['model_year']);
        }


        $res = $this->offer->setItemDetails($this->item_value, $item, $this->item_title, $this->item_desc);

        if ($res) {
            $this->alert('success', 'Item updated');
            $this->mount($this->offer->id);
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
            ->byType($this->offer->type)
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




        if ($this->offer->is_medical) {
            foreach ($this->available_pols as $pol) {
                if ($pol['cond']['id'] == $this->conditionId) {
                    $this->insured_value =  $pol['gross_value'];
                    $this->netPremium = $pol['net_value'];
                    $this->grossPremium = $pol['gross_value'];
                    break;
                }
            }
        } else {

            $this->insured_value = $this->offer->item_value;

            if ($this->conditionData) {
                $this->netPremium = round($this->offer->item_value * ($this->conditionData->rate / 100), 2);
            }
            if ($this->policyData && $this->netPremium) {
                $this->grossPremium = round($this->policyData->calculateGrossValue($this->netPremium), 2);
            }
        }
    }

    public function addOption()
    {
        $this->validate(
            [
                'policyId' => 'required|integer|exists:policies,id',
                'conditionId' => 'nullable|integer|exists:policy_conditions,id',
                'insured_value' => 'nullable|numeric',
                'payment_frequency' => 'nullable|in:' . implode(',', OfferOption::PAYMENT_FREQS),
                'grossPremium' => 'nullable|numeric',
                'netPremium' => 'nullable|numeric',
                'installmentsCount' => 'nullable|numeric',
                'optionIsRenewal' => 'boolean',
                'files' => 'nullable|array',
                'files.*' => 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
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

    public function openSetRenewal()
    {
        $this->setRenewalSec = true;
    }

    public function closeSetRenewal()
    {
        $this->setRenewalSec = false;
    }

    public function updatedsearchPolicyText()
    {
        $this->searchedPolicies = SoldPolicy::userdata($this->searchPolicyText)
            ->get()
            ->take(5);
    }

    public function selectRenewalPolicy($id)
    {
        $this->selectedPolicy = SoldPolicy::findOrFail($id);
        $this->searchPolicyText = null;
        $this->searchedPolicies = null;
    }

    public function clearSelectedPolicy()
    {
        $this->selectedPolicy = null;
    }

    public function setIsRenewal()
    {
        $this->validate([
            'selectedPolicy' => 'required'
        ], messages: [
            'selectedPolicy.required' => 'Sold policy is required.'
        ]);
        SoldPolicy::findOrFail($this->selectedPolicy->id);
        $res = $this->offer->setRenewalFlag(true, $this->selectedPolicy->id);
        if ($res) {
            $this->closeSetRenewal();
            $this->alert('success', 'Renewal Status Changed!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function removeRenewal()
    {
        $res = $this->offer->setRenewalFlag(false,);
        if ($res) {
            $this->alert('success', 'Renewal removed!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function mount($offerId)
    {
        $this->offer = Offer::with('item')->find($offerId);
        $this->authorize('view', $this->offer);
        $this->item_value = $this->offer->item_value;
        $this->item_title = $this->offer->item_title;
        $this->item_desc = $this->offer->item_desc;

        $this->lineFields = [];
        foreach ($this->offer->fields as $field) {
            $this->lineFields[$field->id] = [
                'field' => $field->field,
                'value' => $field->value,
                'is_mandatory' => $field->is_mandatory,
            ];
        }

        $this->dueDate = Carbon::parse($this->offer->due)->toDateString();
        $this->dueTime = Carbon::parse($this->offer->due)->toTimeString();

        $this->profilesRes = collect();

        //watchers code
        $this->watchersList = $this->offer->watcher_ids;
    }

    public function setStatus($s = null)
    {
        if ($s == Offer::STATUS_PENDING_INSUR || $s == Offer::STATUS_PENDING_CUSTOMER) {
            $this->subStatusOfferStatus = $s;
            $this->subStatusSection = true;
            return;
        }

        if ($s == null) return;
        $res = $this->offer->setStatus($s);
        if ($res) {
            $this->alert('info', $res);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setSubStatus()
    {
        $this->validate([
            'subStatus' => 'nullable',
        ]);
        $res = $this->offer->setStatus($this->subStatusOfferStatus, $this->subStatus);
        if ($res) {
            $this->alert('info', $res);
            $this->closeSubStatusSection();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function closeSubStatusSection()
    {
        $this->reset(['subStatusSection', 'subStatus']);
    }

    public function changeOptionState($optionId, $status)
    {
        try {

            $res = $this->offer->setOptionState($optionId, $status);
            if ($res) {
                $this->mount($this->offer->id);
                $this->alert('info', $res);
            } else {
                $this->alert('failed', 'server error');
            }
        } catch (Exception $e) {
            $this->alert('failed', $e->getMessage());
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

    /////medical offer section
    public $showMedicalFileModal = false;
    public $showDownloadPolicyCalculationModal = false;
    public $uploadedMedicalFile;
    public $selectedMedicalPolicyCalculation;

    public function openMedicalFileModal()
    {
        $this->showMedicalFileModal = true;
    }

    public function closeMedicalFileModal()
    {
        $this->showMedicalFileModal = false;
    }
    public function openPolicyCalculationModal()
    {
        $this->showDownloadPolicyCalculationModal = true;
    }

    public function closePolicyCalculationModal()
    {
        $this->showDownloadPolicyCalculationModal = false;
    }

    public function updatedUploadedMedicalFile()
    {
        $this->validate(
            [
                'uploadedMedicalFile' => 'nullable|file|mimes:xlsx|max:33000',
            ],
            [
                'uploadedMedicalFile.max' => 'The file must not be greater than 33MB.',
            ],
        );

        $url = $this->uploadedMedicalFile->store('tmp', 'local');
        $this->offer->importMedicalTemplate(storage_path('app/' . $url));
        unlink(storage_path('app/' . $url));

        $this->alert('success', 'Medical File Uploaded!');
        $this->uploadedMedicalFile = null;
        $this->showMedicalFileModal = null;

        $this->mount($this->offer->id);
    }


    public function downloadCalculatedFile()
    {
        $this->validate(
            [
                'selectedMedicalPolicyCalculation' => 'required',
            ]
        );

        $this->alert('success', 'Medical File Uploaded!');
        $this->showDownloadPolicyCalculationModal = false;
        return $this->offer->downloadCalculatedMedicalTemplate($this->selectedMedicalPolicyCalculation);
    }

    public function downloadMedicalTemplate()
    {
        return $this->offer->downloadMedicalTemplate();
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

    public function openOfferFieldsModal()
    {
        $this->showOfferFieldsModal = true;
    }

    public function closeOfferFieldsModal()
    {
        $this->showOfferFieldsModal = false;
    }

    // File path: app/Http/Livewire/YourComponent.php

    public function setOfferFields()
    {
        $this->validate([
            'lineFields' => 'required|array',
            'lineFields.*.value' => 'nullable|string',
        ]);

        try {
            $formattedFields = [];

            foreach ($this->lineFields as $id => $fieldData) {
                $formattedFields[$id] = [
                    $fieldData['field'] => $fieldData['value']
                ];
                if ($fieldData['is_mandatory'] && !$fieldData['value']) {
                    $this->addError("lineFields.{$id}.value", "Field is mandatory.");
                    return;
                }
            }

            $res = $this->offer->setLineFields($formattedFields);

            if ($res) {
                $this->alert('success', 'Fields updated successfully!');
                $this->showOfferFieldsModal = false;
                $this->mount($this->offer->id);
            } else {
                $this->alert('failed', 'Server error');
            }
        } catch (Exception $e) {
            $this->alert('failed', $e->getMessage());
        }
    }

    public function showPolicyNote($note)
    {
        $this->currentPolicyNote = $note;
        $this->showPolicyNoteModal = true;
    }

    public function hidePolicyNote()
    {
        $this->showPolicyNoteModal = false;
        $this->currentPolicyNote = '';
    }

    public function render()
    {
        $loggedInUser = Auth::user();
        if ($loggedInUser->is_sales) {
            $users = User::operations()->orwhere('id', $loggedInUser->manager_id)->get();
        } else {
            $users = User::active()->get();
        }

        $usersTypes = User::TYPES;
        $STATUSES = Offer::STATUSES;
        $PAYMENT_FREQS = OfferOption::PAYMENT_FREQS;
        $DISCOUNT_TYPES = OfferDiscount::TYPES;
        $optionStatuses = OfferOption::STATUSES;
        $RELATIONS = Relative::RELATIONS;
        $brands = Brand::all();
        $type_policies = Policy::ByType($this->offer->type)
            ->when(in_array($this->offer->type, Policy::MEDICAL_LINES), function ($q) {
                $this->offer->loadCount('medical_offer_clients');
                $q->MedicalLimits($this->offer->medical_offer_clients_count);
            })->get();


        if ($this->offer->item)
            $this->available_pols = Policy::getAvailablePolicies(type: $this->offer->type, car: $this->offer->item, offerValue: $this->offer->item_value);
        elseif (in_array($this->offer->type, Policy::MEDICAL_LINES)) {
            $this->available_pols = Policy::getAvailablePolicies(type: $this->offer->type, offer: $this->offer);
        }

        return view('livewire.offer-show', [
            'STATUSES' => $STATUSES,
            'PAYMENT_FREQS' => $PAYMENT_FREQS,
            'users' => $users,
            'usersTypes' => $usersTypes,
            'DISCOUNT_TYPES' => $DISCOUNT_TYPES,
            'optionStatuses' => $optionStatuses,
            'type_policies' => $type_policies,
            'RELATIONS' => $RELATIONS,
            'brands' => $brands
        ]);
    }
}
