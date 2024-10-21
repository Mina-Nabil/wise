<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Business\SoldPolicy;
use App\Models\Business\SoldPolicyBenefit;
use App\Models\Business\SoldPolicyDoc;
use App\Models\Business\SoldPolicyExclusion;
use App\Models\Insurance\PolicyBenefit;
use App\Models\Offers\OfferOption;
use App\Models\Payments\PolicyComm;
use App\Models\Tasks\TaskAction;
use App\Models\Tasks\TaskField;
use App\Models\Users\User;
use App\Models\Payments\SalesComm;
use App\Models\Payments\ClientPayment;
use App\Models\Payments\CommProfile;
use App\Models\Payments\CompanyCommPayment;
use App\Models\Payments\CommProfileConf;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SoldPolicyShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire, WithFileUploads, AuthorizesRequests;

    public $soldPolicy;
    public $offer;
    public $preview;
    public $issuing_date;
    public $start;
    public $expiry;
    public $policy_number;
    public $car_chassis;
    public $car_plate_no;
    public $car_engine;
    public $in_favor_to;
    public $editInfoSec = false;

    public $deleteBenefitId;
    public $benefitId; //for edit
    public $eBenefit; //edited benefit
    public $eValue; //edited value
    public $newBenefit;
    public $newValue;
    public $newBenefitSec = false;

    public $deleteExcId;
    public $excId;
    public $eExcTitle; //edited exc title
    public $eExcValue; //edited exc value
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
    public $inFavorTo;

    public $note;
    public $noteSection = false;

    public $RemoveCommDocId;
    public $commDoc;
    public $commDocId;
    public $commNote;
    //sales comm
    public $addCommSec = false;
    public $adjustCommSec = false;
    public $commTitle;
    public $commAmount;
    public $commFrom;
    public $commProfile;
    public $commPer;
    public $newcommNote;
    public $commStatus;
    public $deleteCommId;
    public $commId;

    public $deletePolComSec;
    public $updatePolComSec;
    public $policyCommTitle;
    public $policyCommAmount;
    public $newPolComSec = false;

    public $addClientPaymentSec;
    public $paymentType;
    public $paymentAmount;
    public $paymentDue;
    public $paymentNote;
    public $RemovePaymentDocId;
    public $paymentDocId;
    public $paymentDoc;
    public $paymentAssignee;
    public $salesOutID;
    public $paymentNoteSec;
    public $clientPaymentDateSec = false;
    public $clientPaymentDate;
    public $editPaymentSec;

    public $salesOutSelected;
    public $client_payment_date;
    public $setPaidSec;

    public $RemoveCompPaymentDocId;
    public $compPaymentDocId;
    public $compPaymentDoc;
    public $CompPaymentNoteSec;

    public $deleteSoldPolicySec;

    public $addCompanyPaymentSec;
    public $compPaymentType;
    public $compPaymentAmount;
    public $compPaymentNote;

    public $editTotalPolCommSection;
    public $updateTotalPolComm;
    public $updateTotalPolCommNote;

    //for set payment as collected
    public $setPaymentCollectedSec;
    public $payment_collected_note;
    public $paymentCollectedDoc;

    //for set payment as paid
    public $setPaymentPaidSec;
    public $payment_type;
    public $payment_date;

    public $section = 'profile';

    protected $queryString = ['section'];
    public $uploadedFile;

    public function openEditPaymentSec($id)
    {
        $this->editPaymentSec = $id;
        $p = ClientPayment::find($id);
        $this->paymentType = $p->type;
        $this->paymentDue = $p->due;
        $this->paymentNote = $p->note;
        $this->paymentAssignee = $p->assigned_to;
        $this->salesOutID = $p->sales_out_id;
    }

    public function closeEditPaymentSec()
    {
        $this->editPaymentSec = null;
        $this->paymentType = null;
        $this->paymentDue = null;
        $this->paymentNote = null;
        $this->paymentAssignee = null;
        $this->salesOutID = null;
    }

    public function editClientPayment()
    {
        $this->validate([
            'paymentType' => 'required|in:' . implode(',', ClientPayment::PYMT_TYPES),
            'paymentDue' => 'required|date',
            'paymentNote' => 'nullable|string',
            'paymentAssignee' => 'nullable|integer|exists:users,id',
            'salesOutID' => 'nullable|integer|exists:users,id',
        ]);

        $res = ClientPayment::find($this->editPaymentSec)->setInfo(Carbon::parse($this->paymentDue), $this->paymentType, $this->paymentAssignee, $this->paymentNote, $this->salesOutID);
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->closeEditPaymentSec();
            $this->alert('success', 'Payment updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function closeSetPaymentCollectedSec()
    {
        $this->setPaymentCollectedSec = null;
        $this->payment_collected_note = null;
        $this->paymentCollectedDoc = null;
    }

    public function closeSetPaymentPaidSec()
    {
        $this->setPaymentPaidSec = null;
        $this->payment_type = null;
        $this->payment_date = null;
    }

    public function openSetPaymentCollectedSec($id)
    {
        $this->setPaymentCollectedSec = $id;
    }

    public function openSetPaymentPaidSec($id)
    {
        $this->setPaymentPaidSec = $id;
    }

    public function closeEditTotalPolCommSection()
    {
        $this->editTotalPolCommSection = false;
        $this->updateTotalPolComm = null;
        $this->updateTotalPolCommNote = null;
    }

    public function openEditTotalPolCommSection()
    {
        $this->editTotalPolCommSection = true;
        $this->updateTotalPolComm = $this->soldPolicy->total_policy_comm;
        $this->updateTotalPolCommNote = $this->soldPolicy->policy_comm_note;
    }

    public function updateTotalPolComm()
    {
        $this->validate(
            [
                'updateTotalPolComm' => 'required|numeric',
                'updateTotalPolCommNote' => 'nullable|string',
            ],
            attributes: [
                'updateTotalPolComm' => 'total policy commission',
                'updateTotalPolCommNote' => 'total commission note',
            ],
        );

        // dd($this->updateTotalPolCommNote);
        $res = $this->soldPolicy->setPolicyCommission($this->updateTotalPolComm, $this->updateTotalPolCommNote);
        if ($res) {
            $this->closeEditTotalPolCommSection();
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Policy Commission updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function changeSection($section)
    {
        $this->section = $section;
        $this->mount($this->soldPolicy->id);
    }

    public function toggleAddCompanyPayment()
    {
        $this->toggle($this->addCompanyPaymentSec);
    }

    public function addCompanyPayment()
    {
        $this->validate([
            'compPaymentType' => 'required|in:' . implode(',', ClientPayment::PYMT_TYPES),
            'compPaymentAmount' => 'required|numeric',
            'compPaymentNote' => 'nullable|string',
        ]);

        if (!($this->compPaymentAmount <= $this->soldPolicy->total_policy_comm - $this->soldPolicy->total_company_paid)) {
            $this->throwError('compPaymentAmount', 'Amount is more that what the company should pay. Please make sure the amount is less than the total commission plus the company payments total.');
        }

        $res = $this->soldPolicy->addCompanyPayment($this->compPaymentType, $this->compPaymentAmount, $this->compPaymentNote);
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->addCompanyPaymentSec = false;
            $this->compPaymentType = null;
            $this->compPaymentAmount = null;
            $this->compPaymentNote = null;
            $this->alert('success', 'Payment added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function openPaymentDateSec()
    {
        $this->clientPaymentDateSec = true;
        if ($this->clientPaymentDate) {
            $this->clientPaymentDate = Carbon::parse($this->clientPaymentDate)->toDateString();
        }
    }

    public function closePaymentDateSec()
    {
        $this->clientPaymentDate = $this->soldPolicy->client_payment_date;
        $this->clientPaymentDateSec = false;
    }

    public function changePaymentDate()
    {
        $res = $this->soldPolicy->setClientPaymentDate(Carbon::parse($this->clientPaymentDate));
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'date updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function showPaymentNote($id)
    {
        $n = ClientPayment::find($id);
        $this->paymentNoteSec = $n->note;
    }

    public function downloadPaymentDoc($id)
    {
        $payment = ClientPayment::find($id);
        $fileContents = Storage::disk('s3')->get($payment->doc_url);
        $extension = pathinfo($payment->doc_url, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $this->soldPolicy->policy_number . '_client_payment_document.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function hidePaymentComment()
    {
        $this->paymentNoteSec = null;
    }

    public function removePaymentDoc()
    {
        $res = ClientPayment::find($this->RemovePaymentDocId)->deleteDocument();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->RemovePaymentDocId = null;
            $this->alert('success', 'payment removed');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function updatedPaymentDoc()
    {
        $this->validate([
            'paymentDoc' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
        ]);

        $url = $this->paymentDoc->store(ClientPayment::FILES_DIRECTORY, 's3');

        $res = ClientPayment::find($this->paymentDocId)->setDocument($url);
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'document added');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function ConfirmRemovePaymentDoc($id)
    {
        $this->RemovePaymentDocId = $id;
    }

    public function DissRemovePaymentDoc($id)
    {
        $this->RemovePaymentDocId = null;
    }

    public function setPaymentDoc($id)
    {
        $this->paymentDocId = $id;
    }

    public function downloadCompPaymentDoc($id)
    {
        $payment = CompanyCommPayment::find($id);
        $fileContents = Storage::disk('s3')->get($payment->doc_url);
        $extension = pathinfo($payment->doc_url, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $this->soldPolicy->policy_number . '_company_payment_document.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function showCompPaymentNote($id)
    {
        $n = CompanyCommPayment::find($id);
        $this->CompPaymentNoteSec = $n->note;
    }

    public function hideCompPaymentComment()
    {
        $this->CompPaymentNoteSec = null;
    }

    public function updatedCompPaymentDoc()
    {
        $this->validate([
            'compPaymentDoc' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
        ]);

        $url = $this->compPaymentDoc->store(CompanyCommPayment::FILES_DIRECTORY, 's3');

        $res = CompanyCommPayment::find($this->compPaymentDocId)->setDocument($url);
        if ($res) {
            $this->compPaymentDocId = null;
            $this->compPaymentDoc = null;
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'document added');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setCompanyPaymentPaid($id)
    {
        $res = CompanyCommPayment::find($id)->setAsPaid();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Payment updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setCompanyPaymentCancelled($id)
    {
        $res = CompanyCommPayment::find($id)->setAsCancelled();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Payment updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function ConfirmRemoveCompPaymentDoc($id)
    {
        $this->RemoveCompPaymentDocId = $id;
    }

    public function DissRemoveCompPaymentDoc()
    {
        $this->RemoveCompPaymentDocId = null;
    }

    public function removeCompPaymentDoc()
    {
        $res = CompanyCommPayment::find($this->RemoveCompPaymentDocId)->deleteDocument();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->RemoveCompPaymentDocId = null;
            $this->alert('success', 'payment removed');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setCompPaymentDoc($id)
    {
        $this->compPaymentDocId = $id;
    }

    public function setPaymentPaid()
    {
        $this->validate([
            'payment_type' => 'required|in:' . implode(',', ClientPayment::PYMT_TYPES),
            'payment_date' => 'required|date',
        ]);

        $res = ClientPayment::find($this->setPaymentPaidSec)->setAsPaid($this->payment_type, Carbon::parse($this->payment_date));
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->closeSetPaymentPaidSec();
            $this->alert('success', 'Payment updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setPaymentCollected()
    {
        $this->validate([
            'paymentCollectedDoc' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
        ]);
        if ($this->paymentCollectedDoc) {
            $url = $this->paymentCollectedDoc->store(SalesComm::FILES_DIRECTORY, 's3');
        } else {
            $url = null;
        }

        $res = ClientPayment::find($this->setPaymentCollectedSec)->setAsPremiumCollected($url, $this->payment_collected_note);
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->closeSetPaymentCollectedSec();
            $this->alert('success', 'Payment updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setPaymentCancelled($id)
    {
        $res = ClientPayment::find($id)->setAsCancelled();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Payment updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deleteClientPayment($id)
    {
        $res = ClientPayment::find($id)?->deletePayment();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Payment deleted!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function updatedPaymentType()
    {
        $this->salesOutID = null;
        if ($this->paymentType == ClientPayment::PYMT_TYPE_SALES_OUT) {
            $this->salesOutSelected = true;
        } else {
            $this->salesOutSelected = false;
        }
    }

    public function addClientPayment()
    {
        $this->validate(
            [
                'paymentType' => 'required|in:' . implode(',', ClientPayment::PYMT_TYPES),
                'paymentAmount' => 'required|numeric',
                'paymentDue' => 'required|date',
                'paymentNote' => 'nullable|string',
                'paymentAssignee' => 'nullable|integer|exists:users,id',
                'salesOutID' => 'required_if:paymentType,' . ClientPayment::PYMT_TYPE_SALES_OUT . '|nullable|exists:sales_comms,id',
            ],
            [
                'salesOutID' => 'Must select a profile if the payment type is Sales Out',
            ],
        );

        $res = $this->soldPolicy->addClientPayment($this->paymentType, $this->paymentAmount, Carbon::parse($this->paymentDue), $this->paymentAssignee, $this->paymentNote, $this->salesOutID);
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->addClientPaymentSec = false;
            $this->paymentType = null;
            $this->paymentAmount = null;
            $this->paymentDue = null;
            $this->paymentNote = null;
            $this->paymentAssignee = null;
            $this->salesOutID = null;
            $this->alert('success', 'Payment added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function toggleAddClientPayment()
    {
        $this->toggle($this->addClientPaymentSec);
    }

    public function openUpdatePolCom($id)
    {
        $res = PolicyComm::find($id);
        $this->policyCommAmount = $res->amount;
        $this->updatePolComSec = $id;
    }

    public function closeUpdatePolCom()
    {
        $this->updatePolComSec = null;
    }

    public function openNewPolCom()
    {
        $this->authorize('updateWiseCommPayments', $this->soldPolicy);
        $this->newPolComSec = true;
    }

    public function closeNewPolCom()
    {
        $this->newPolComSec = false;
        $this->reset(['policyCommTitle', 'policyCommAmount']);
    }

    public function newPolicyComm()
    {
        $this->authorize('updateWiseCommPayments', $this->soldPolicy);

        $this->validate([
            'policyCommTitle' => 'required|string|max:255',
            'policyCommAmount' => 'required|numeric|min:1',
        ]);

        $res = $this->soldPolicy->addPolicyCommission($this->policyCommTitle, $this->policyCommAmount);

        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->closeNewPolCom();
            $this->alert('success', 'Commission added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function updateCommAmount()
    {
        $this->validate([
            'policyCommAmount' => 'required|numeric',
        ]);
        $res = PolicyComm::find($this->updatePolComSec)->editAmount($this->policyCommAmount);
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->updatePolComSec = null;
            $this->alert('success', 'Commission updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function confirmDeletePolCom($id)
    {
        $this->deletePolComSec = $id;
    }

    public function dismissDeletePolCom()
    {
        $this->deletePolComSec = null;
    }

    public function deletePolicyComm()
    {
        $res = PolicyComm::find($this->deletePolComSec)->deleteCommission();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->deletePolComSec = null;
            $this->alert('success', 'Commission deleted!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function generatePolicyCommission()
    {
        $res = $this->soldPolicy->generatePolicyCommissions();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Commissions generated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

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
            $url = $file->store(SoldPolicyDoc::FILES_DIRECTORY, 's3');
            $o = $this->soldPolicy->addFile($filename, $url);
        }

        if ($o) {
            $this->alert('success', 'File Uploaded!');
            $this->uploadedFile = null;
            $this->mount($this->soldPolicy->id);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

    public function downloadSoldPolicyFile($id)
    {
        $doc = SoldPolicyDoc::findOrFail($id);

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

    public function removeSoldPolicyFile($id)
    {
        $res = SoldPolicyDoc::find($id)->delete();
        if ($res) {
            $this->alert('success', 'File Deleted');
            $this->mount($this->soldPolicy->id);
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function addComm()
    {
        $this->validate([
            'commTitle' => 'required|string|max:255',
            'commPer' => 'required|numeric',
            'commProfile' => 'nullable|integer|exists:comm_profiles,id',
            'commNote' => 'nullable|string',
            'commFrom' => 'required|in:' . implode(',', CommProfileConf::FROMS),
        ]);

        $res = $this->soldPolicy->addSalesCommission($this->commTitle, $this->commFrom, $this->commPer, $this->commProfile, $this->newcommNote);
        if ($res) {
            $this->toggleAddComm();
            $this->commTitle = null;
            $this->commPer = null;
            $this->commProfile = null;
            $this->newcommNote = null;
            $this->commFrom = null;
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Commission added!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function toggleAddComm()
    {
        $this->toggle($this->addCommSec);

        if (!$this->addCommSec) {
            $this->reset(['commTitle', 'commFrom', 'commPer', 'commProfile', 'newcommNote']);
        }
    }

    public function toggleAdjustComm()
    {
        $this->toggle($this->adjustCommSec);

        if (!$this->adjustCommSec) {
            $this->reset(['commFrom', 'commAmount', 'commProfile', 'newcommNote']);
        }
    }

    public function adjustComm()
    {
        $this->authorize('updatePayments',$this->soldPolicy);

        $this->validate([
            'commAmount' => 'required|numeric|min:1',
            'commProfile' => 'nullable|integer|exists:comm_profiles,id',
            'commNote' => 'nullable|string',
            'commFrom' => 'required|in:' . implode(',', CommProfileConf::FROMS),
        ]);

        $res = $this->soldPolicy->adjustSalesCommission($this->commFrom,$this->commAmount,$this->commProfile,$this->commNote);

        if ($res) {
            $this->toggleAdjustComm();
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Commission added!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function showCommNote($id)
    {
        $n = SalesComm::find($id);
        $this->commNote = $n->note;
    }

    public function hideCommComment()
    {
        $this->commNote = null;
    }

    public function setCommDoc($id)
    {
        $this->commDocId = $id;
    }

    public function updatedCommDoc()
    {
        $this->validate([
            'commDoc' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
        ]);

        $url = $this->commDoc->store(SalesComm::FILES_DIRECTORY, 's3');

        $res = SalesComm::find($this->commDocId)->setDocument($url);
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'document added');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function downloadCommDoc($id)
    {
        $comm = SalesComm::find($id);
        $fileContents = Storage::disk('s3')->get($comm->doc_url);
        $extension = pathinfo($comm->doc_url, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $this->soldPolicy->policy_number . '_sale_comm_document.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function ConfirmRemoveCommDoc($id)
    {
        $this->RemoveCommDocId = $id;
    }

    public function DissRemoveCommDoc($id)
    {
        $this->RemoveCommDocId = null;
    }

    public function removeCommDoc()
    {
        $res = SalesComm::find($this->RemoveCommDocId)->deleteDocument();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->RemoveCommDocId = null;
            $this->alert('success', 'Commission updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setCommPaid($id)
    {
        $res = SalesComm::find($id)->setAsPaid();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Commission updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setCommCancelled($id)
    {
        $res = SalesComm::find($id)->setAsCancelled();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Commission updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function refreshCommAmmount($id)
    {
        $res = SalesComm::find($id)->refreshPaymentInfo();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'Commission updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function toggleNoteSection()
    {
        $this->toggle($this->noteSection);
        if ($this->noteSection) {
            $this->note = $this->soldPolicy->note;
        }
    }

    public function editNote()
    {
        $this->validate([
            'note' => 'required|string|max:255',
        ]);

        $res = $this->soldPolicy->setNote($this->note);

        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->toggleNoteSection();
            $this->alert('success', 'note updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function toggleGenerateRenewalOfferSec()
    {
        $this->toggle($this->generateRenewalOfferSec);
    }

    public function generateRenewalOffer()
    {
        $res = $this->soldPolicy->generateRenewalOffer(Carbon::parse($this->renewalOfferDue), $this->inFavorTo);
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->toggleGenerateRenewalOfferSec();
            $this->alert('success', 'document deleted');
            $this->dispatchBrowserEvent('openNewTab', ['url' => '/offers' . '/' . $res->id]);
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
            'docFile' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
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

    public function openSetPaidSec()
    {
        $this->setPaidSec = true;
    }

    public function closeSetPaidSec()
    {
        $this->setPaidSec = false;
        $this->client_payment_date = null;
    }

    public function setPaid()
    {
        $this->validate([
            'client_payment_date' => 'required|date',
        ]);
        $res = $this->soldPolicy->setPaid(1, Carbon::parse($this->client_payment_date));
        if ($res) {
            $this->closeSetPaidSec();
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'paid info updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setUnpaid()
    {
        $res = $this->soldPolicy->setNotPaid();
        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->alert('success', 'paid info updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function openEditInfoSection()
    {
        $this->editInfoSec = true;
        $this->start = Carbon::parse($this->soldPolicy->start)->toDateString();
        $this->expiry = Carbon::parse($this->soldPolicy->expiry)->toDateString();
        $this->issuing_date = Carbon::parse($this->soldPolicy->created_at)->toDateString();
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
            'issuing_date' => 'required|date',
            'policy_number' => 'required|string|max:255',
            'car_chassis' => 'nullable|string|max:255',
            'car_plate_no' => 'nullable|string|max:255',
            'car_engine' => 'nullable|string|max:255',
            'in_favor_to' => 'nullable|string|max:255',
        ]);

        $res = $this->soldPolicy->editInfo(Carbon::parse($this->start), Carbon::parse($this->expiry), $this->policy_number, $this->car_chassis, $this->car_plate_no, $this->car_engine, $this->in_favor_to, Carbon::parse($this->issuing_date));

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

    public function createTask()
    {
        $this->validate([
            'newTaskDesc' => 'nullable|string',
            'newTaskDue' => 'nullable|date',
        ]);

        $res = $this->soldPolicy->addTaskToOperations(Carbon::parse($this->newTaskDue), $this->newTaskDesc);

        if ($res) {
            $this->mount($this->soldPolicy->id);
            $this->closeNewTaskSection();
            $this->alert('success', 'Task added!');
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

    public function toggleDeleteSoldPolicy()
    {
        $this->deleteSoldPolicySec = !$this->deleteSoldPolicySec;
    }

    public function deleteSoldPolicy()
    {
        $res = $this->soldPolicy->deleteSoldPolicy();
        if ($res) {
            $this->alert('success', 'Sold Policy deleted');
            return redirect(route('sold.policy.index'));
        } else {
            $this->alert('danger', 'Unable to delete');
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

        $res = $this->soldPolicy->updatePaymentInfo($this->insured_value, $this->net_rate, $this->net_premium, $this->gross_premium, $this->installements_count, $this->payment_frequency, $this->discount);

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
            'newValue' => 'required|string|max:255',
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
            'eValue' => 'required|string|max:255',
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
            'newExcValue' => 'required|string|max:255',
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
            'eExcValue' => 'required|string|max:255',
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
        $this->validate(
            [
                'setWatchersList' => 'nullable|array',
                'setWatchersList.*' => 'integer|exists:users,id',
            ],
            [],
            [
                'setWatchersList' => 'Watchers',
            ],
        );

        $t = $this->soldPolicy->setWatchers($this->setWatchersList);
        if ($t) {
            $this->alert('success', 'Watchers Updated!');
            $this->closeChangeWatchers();
            $this->mount($this->soldPolicy->id);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

    public function mount($id)
    {
        $this->soldPolicy = SoldPolicy::with('company_comm_payments')->find($id);
        $this->offer = $this->soldPolicy->offer;
        $this->insured_value = $this->soldPolicy->insured_value;
        $this->net_rate = $this->soldPolicy->net_rate;
        $this->net_premium = $this->soldPolicy->net_premium;
        $this->gross_premium = $this->soldPolicy->gross_premium;
        $this->installements_count = $this->soldPolicy->installements_count;
        $this->payment_frequency = $this->soldPolicy->payment_frequency;
        $this->discount = $this->soldPolicy->discount;
        $this->clientPaymentDate = $this->soldPolicy->client_payment_date ?? null;
        $this->actions[] = ['column_name' => '', 'value' => ''];
        $this->fields[] = ['title' => '', 'value' => ''];
        //watchers code
        $this->watchersList = $this->soldPolicy->watcher_ids;
    }

    public function render()
    {
        $BENEFITS = PolicyBenefit::BENEFITS;
        $PAYMENT_FREQS = OfferOption::PAYMENT_FREQS;
        $COLUMNS = TaskAction::COLUMNS[TaskAction::TABLE_SOLD_POLICY];
        $FIELDSTITLES = TaskField::TITLES;
        $users = User::all();
        $PYMT_TYPES = ClientPayment::PYMT_TYPES;
        $FROMS = CommProfileConf::FROMS;
        $CommProfiles = CommProfile::all();
        // $linkedCommProfiles = CommProfile::linkedToSoldPolicy($this->soldPolicy->id)->get();
        $linkedCommProfiles = CommProfile::all();
        $salesOuts = CommProfile::salesOut()->get();

        return view('livewire.sold-policy-show', [
            'BENEFITS' => $BENEFITS,
            'PAYMENT_FREQS' => $PAYMENT_FREQS,
            'COLUMNS' => $COLUMNS,
            'FIELDSTITLES' => $FIELDSTITLES,
            'users' => $users,
            'PYMT_TYPES' => $PYMT_TYPES,
            'FROMS' => $FROMS,
            'CommProfiles' => $CommProfiles,
            'linkedCommProfiles' => $linkedCommProfiles,
            'salesOuts' => $salesOuts,
        ]);
    }
}
