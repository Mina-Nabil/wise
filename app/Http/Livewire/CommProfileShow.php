<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\CommProfile;
use App\Models\Users\User;
use App\Models\Payments\CommProfileConf;
use App\Models\Payments\Target;
use App\Models\Payments\TargetRun;
use App\Models\Payments\TargetCycle;
use App\Models\Payments\CommProfilePayment;
use App\Models\Insurance\Policy;
use App\Models\Insurance\Company;
use App\Models\Payments\ClientPayment;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Models\Payments\SalesComm;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class CommProfileShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire, WithFileUploads, AuthorizesRequests;
    public $profile;

    public $updatedCommSec = false;
    public $paymentNoteSec = false;

    public $updatedType;
    public $updatedPerPolicy;
    public $updatedAutomaticOverrideId;
    public $updatedSelectAvailable;
    public $updatedUserId;
    public $updatedTitle;
    public $updatedDesc;

    public $editedRowId;

    public $newConfSec;
    public $percentage;
    public $from;
    public $condition;
    public $line_of_business;
    public $conditionType = 'company';

    public $searchCon;
    public $searchlist;

    public $deleteConfId;
    public $editConfId;

    public $newTargetSec;
    public $dayOfMonth; //
    public $eachMonth; //
    public $premTarget; //
    public $minIncomeTarget; //
    public $maxIncomeTarget; //
    public $nextRunDate; //
    public $commPercentage; //
    public $addToBalance;
    public $addAsPayment;
    public $basePayment;
    public $isEndOfMonth;
    public $isFullAmount;

    public $deleteTargetId;
    public $editTargetId;

    public $deleteCycleId;
    public $editCycleId;

    public $newPymtSec = false;
    public $pymtAmount;
    public $pymtType;
    public $pymtDoc;
    public $pymtNote;
    public $pymtPaidId;
    public $pymtPaidDate;
    public $pymtCancelledId;
    public $pymtCancelledDate;
    public $pymtNotePreview;
    public $pymtDeleteId;
    public $uploadPymtDocId;
    public $pymtDocFile;
    public $pymtId;

    public $commNote;
    public $RemoveCommDocId;
    public $commDocId;
    public $commDoc;
    public $addCommSec;
    public $commTitle;
    public $commFrom;
    public $commPer;
    public $commUser;
    public $newcommNote;

    public $runs;
    public $startTargetRunSection;
    public $startTargetRunEndDate;

    public $downloadAccountStatementSec;
    public $downloadAccountStartDate;
    public $downloadAccountEndDate;


    protected $listeners = ['deleteProfile']; //functions need confirmation

    public $section = 'payments';

    protected $queryString = ['section'];

    public function changeSection($section)
    {
        $this->section = $section;
        $this->mount($this->profile->id);
    }

    public function openDownloadAccountStatement(){
        $this->downloadAccountStatementSec = true;
    }

    public function closeDownloadAccountStatementSec(){
        $this->downloadAccountStatementSec = false;
    }

    public function downloadAccountStatement(){
        $this->validate([
            'downloadAccountStartDate' => 'required|date',
            'downloadAccountEndDate' => 'required|date'
        ],attributes:[
            'downloadAccountStartDate' => 'start date',
            'downloadAccountEndDate' => 'end date'
        ]);

        $res = $this->profile->downloadAccountStatement(Carbon::parse($this->downloadAccountStartDate),Carbon::parse($this->downloadAccountEndDate));

        if ($res) {
            $this->mount($this->profile->id);
            $this->reset(['downloadAccountStatementSec' ,'downloadAccountStartDate' ,'downloadAccountEndDate' ]);
            $this->alert('success', 'Statement downloaded!');
            return $res;
        } else {
            $this->alert('failed', 'server error');
        }

    }

    public function deleteProfile(){
        $res = $this->profile->deleteProfile();
        if ($res) {
            return redirect(route('comm.profile.index'));
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function openStartTargetRunSec(){
        $this->startTargetRunSection = true;
    }

    public function closeStartTargetRunSec(){
        $this->reset(['startTargetRunSection' ,'startTargetRunEndDate']);
    }

    public function startManualTargetsRun(){
        $this->validate([
            'startTargetRunEndDate' => 'required|date'
        ]);

        $res = $this->profile->startManualTargetsRun(Carbon::parse($this->startTargetRunEndDate));

        if ($res) {
            $this->mount($this->profile->id);
            $this->closeStartTargetRunSec();
            $this->alert('success', 'Target Run Intiated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function showTagetRuns($id)
    {
        $this->runs = Target::find($id)->runs()->get();
    }

    public function closeTargetRuns()
    {
        $this->reset(['runs']);
    }

    public function hideCommComment()
    {
        $this->commNote = null;
    }

    public function DissRemoveCommDoc()
    {
        $this->RemoveCommDocId = null;
    }

    public function removeCommDoc()
    {

        $res = SalesComm::find($this->RemoveCommDocId)->deleteDocument();
        if ($res) {
            $this->mount($this->profile->id);
            $this->RemoveCommDocId = null;
            $this->alert('success', 'Commission updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function toggleAddComm()
    {
        $this->toggle($this->addCommSec);
    }

    public function ConfirmRemoveCommDoc($id)
    {
        $this->RemoveCommDocId = $id;
    }

    public function setCommDoc($id)
    {
        $this->commDocId = $id;
    }


    public function refreshCommAmmount($id)
    {

        $res =  SalesComm::find($id)->refreshPaymentInfo();
        if ($res) {
            $this->mount($this->profile->id);
            $this->alert('success', 'Commission updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setCommCancelled($id)
    {

        $res =  SalesComm::find($id)->setAsCancelled();
        if ($res) {
            $this->mount($this->profile->id);
            $this->alert('success', 'Commission updated');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setCommPaid($id)
    {

        $res =  SalesComm::find($id)->setAsPaid();
        if ($res) {
            $this->mount($this->profile->id);
            $this->alert('success', 'Commission updated');
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
            'Content-Disposition' => 'attachment; filename="' . $this->profile->title . '_sale_comm_document.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function showCommNote($id)
    {
        $n = SalesComm::find($id);
        $this->commNote = $n->note;
    }

    public function updatedCommDoc()
    {

        $this->validate([
            'commDoc' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
        ]);

        $url = $this->commDoc->store(SalesComm::FILES_DIRECTORY, 's3');

        $res = SalesComm::find($this->commDocId)->setDocument($url);
        if ($res) {
            $this->mount($this->profile->id);
            $this->alert('success', 'document added');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function closeEditPymtSection()
    {
        $this->pymtId = null;
        $this->pymtAmount = null;
        $this->pymtType = null;
        $this->pymtNote = null;
    }

    public function editThisPayment($id)
    {
        $this->pymtId = $id;
        $p = CommProfilePayment::find($id);
        $this->pymtAmount = $p->amount;
        $this->pymtType = $p->type;
        $this->pymtNote = $p->note;
    }

    public function editPayment()
    {

        if (($this->pymtAmount) > ($this->profile->balance + $this->profile->unapproved_balance)) {
            throw ValidationException::withMessages([
                'pymtAmount' => 'Payment amount cannot exceed your balance.'
            ]);
        }

        $this->validate([
            'pymtAmount' => 'required|numeric|gt:0',
            'pymtType' => 'required|in:' . implode(',', CommProfilePayment::PYMT_TYPES),
            'pymtNote' => 'nullable|string'
        ]);

        $res = CommProfilePayment::find($this->pymtId)->setInfo($this->pymtAmount, $this->pymtType, $this->pymtNote);
        if ($res) {
            $this->closeEditPymtSection();
            $this->mount($this->profile->id);
            $this->alert('success', 'payment updated!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function setUploadPymtDocId($id)
    {
        $this->uploadPymtDocId = $id;
    }

    public function updatedPymtDocFile()
    {
        $this->validate(
            [
                'pymtDocFile' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
            ],
            [
                'pymtDocFile.max' => 'The file must not be greater than 5MB.',
            ],
        );

        $url = $this->pymtDocFile->store(CommProfilePayment::FILES_DIRECTORY, 's3');
        $pymt = CommProfilePayment::find($this->uploadPymtDocId);
        $p = $pymt->setDocument($url);
        if ($p) {
            $this->uploadPymtDocId = null;
            $this->pymtDocFile = null;
            $this->mount($this->profile->id);
            $this->alert('success', 'document uploaded!');
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }


    public function dismissDeletePymtDoc()
    {
        $this->pymtDeleteId = null;
    }

    public function deleteThisPymtDoc($id)
    {
        $this->pymtDeleteId = $id;
    }

    public function deletePymtDoc()
    {
        $res = CommProfilePayment::find($this->pymtDeleteId)->deleteDocument();
        if ($res) {
            $this->dismissDeletePymtDoc();
            $this->mount($this->profile->id);
            $this->alert('success', 'payment approved!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function downloadPymtDoc($id)
    {
        $pymt = CommProfilePayment::find($id);
        $fileContents = Storage::disk('s3')->get($pymt->doc_url);
        $extension = pathinfo($pymt->doc_url, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $this->profile->title . '_payment.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function showPymtNote($id)
    {
        $note = CommProfilePayment::find($id)->note;
        // dd('hello');
        $this->pymtNotePreview = $note;
    }

    public function closePymtNote()
    {
        $this->pymtNotePreview = null;
    }

    public function setPymtApprove($id)
    {
        $res = CommProfilePayment::find($id)->approve();
        if ($res) {
            $this->mount($this->profile->id);
            $this->alert('success', 'payment approved!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function setCancelledSec($id)
    {
        $this->pymtCancelledId = $id;
    }

    public function closeSetCancelledSec()
    {
        $this->pymtCancelledId = null;
    }

    public function setPymtCancelled()
    {
        $res = CommProfilePayment::find($this->pymtCancelledId)->setAsCancelled(Carbon::parse($this->pymtCancelledDate));
        if ($res) {
            $this->closeSetCancelledSec();
            $this->mount($this->profile->id);
            $this->alert('success', 'payment cancelled!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function setPymtPaid()
    {
        $res = CommProfilePayment::find($this->pymtPaidId)->setAsPaid(Carbon::parse($this->pymtPaidDate));
        if ($res) {
            $this->closeSetPaidSec();
            $this->mount($this->profile->id);
            $this->alert('success', 'payment added!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function setPaidSec($id)
    {
        $this->pymtPaidId = $id;
    }

    public function closeSetPaidSec()
    {
        $this->pymtPaidId = null;
    }

    public function addPayment()
    {
        if (($this->pymtAmount) > ($this->profile->balance + $this->profile->unapproved_balance)) {
            throw ValidationException::withMessages([
                'pymtAmount' => 'Payment amount cannot exceed your balance.'
            ]);
        }

        $this->validate([
            'pymtAmount' => 'required|numeric|gt:0',
            'pymtType' => 'required|in:' . implode(',', CommProfilePayment::PYMT_TYPES),
            'pymtDoc' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
            'pymtNote' => 'nullable|string'
        ]);

        if ($this->pymtDoc) {
            $docUrl = $this->pymtDoc->store(CommProfilePayment::FILES_DIRECTORY, 's3');
        } else {
            $docUrl = null;
        }

        $res = $this->profile->addPayment($this->pymtAmount, $this->pymtType, $docUrl, $this->pymtNote);

        if ($res) {
            $this->closeNewPymtSection();
            $this->mount($this->profile->id);
            $this->alert('success', 'payment added!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function openNewPymtSection()
    {
        $this->newPymtSec = true;
    }

    public function closeNewPymtSection()
    {
        $this->newPymtSec = false;
        $this->pymtAmount = null;
        $this->pymtType = null;
        $this->pymtDoc = null;
        $this->pymtNote = null;
    }

    public function closeNewTargetSection()
    {
        $this->newTargetSec = false;
        $this->dayOfMonth = null;
        $this->eachMonth = null;
        $this->premTarget = null;
        $this->minIncomeTarget = null;
        $this->maxIncomeTarget = null;
        $this->nextRunDate = null;
        $this->commPercentage = null;
        $this->addToBalance = 100;
        $this->addAsPayment = 100;
        $this->basePayment = null;
        $this->isEndOfMonth = false;
        $this->isFullAmount = false;
    }

    public function openNewTargetSection()
    {
        $this->newTargetSec = true;
    }

    public function editThisTarget($id)
    {
        $this->editTargetId = $id;
        $t = Target::find($id);
        $this->premTarget = $t->prem_target;
        $this->dayOfMonth = $t->day_of_month;
        $this->isEndOfMonth = $t->is_end_of_month;
        $this->isFullAmount = $t->is_full_amount;
        $this->eachMonth = $t->each_month;
        $this->premTarget = $t->prem_target;
        $this->minIncomeTarget = $t->min_income_target;
        $this->maxIncomeTarget = $t->max_income_target;
        // $this->nextRunDate = $t->next_run_date;
        $this->commPercentage = $t->comm_percentage;
        $this->addToBalance = $t->add_to_balance;
        $this->addAsPayment = $t->add_as_payment;
        $this->basePayment = $t->base_payment;
    }

    public function closeEditTargetSection()
    {
        $this->editTargetId = null;
        $this->dayOfMonth = null;
        $this->eachMonth = null;
        $this->premTarget = null;
        $this->minIncomeTarget = null;
        $this->maxIncomeTarget = null;
        $this->nextRunDate = null;
        $this->commPercentage = null;
        $this->addToBalance = 100;
        $this->addAsPayment = 100;
        $this->basePayment = null;
        $this->isEndOfMonth = false;
        $this->isFullAmount = false;
    }

    public function editarget()
    {
        $this->validate([
            'minIncomeTarget' => 'required|numeric',
            'dayOfMonth' => 'required_unless:isEndOfMonth,true|between:0,31',
            'eachMonth' => 'required|numeric',
            'premTarget' => 'required|numeric',
            'commPercentage' => 'required|numeric',
            'maxIncomeTarget' => 'nullable|numeric',
            'addToBalance' => 'required|numeric',
            'addAsPayment' => 'required|numeric',
            'basePayment' => 'nullable|numeric',
            'nextRunDate' => 'nullable|date',
            'isFullAmount' => 'nullable|boolean',
        ]);

        $res = Target::find($this->editTargetId)->editInfo(
            $this->isEndOfMonth ? 28 : $this->dayOfMonth,
            $this->eachMonth,
            $this->premTarget,
            $this->minIncomeTarget,
            $this->commPercentage,
            $this->addToBalance,
            $this->addAsPayment,
            $this->basePayment,
            $this->maxIncomeTarget,
            $this->nextRunDate ? new Carbon($this->nextRunDate) : null,
            $this->isEndOfMonth,
            $this->isFullAmount,
        );
        if ($res) {
            $this->closeEditTargetSection();
            $this->mount($this->profile->id);
            $this->alert('success', 'target updated!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function dismissDeleteTarget()
    {
        $this->deleteTargetId = null;
    }

    public function confirmDeleteTarget($id)
    {
        $this->deleteTargetId = $id;
    }


    public function deleteTarget()
    {
        $res = Target::find($this->deleteTargetId)->deleteTarget();
        if ($res) {
            $this->dismissDeleteTarget();
            $this->mount($this->profile->id);
            $this->alert('success', 'target deleted!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function addTarget()
    {

        $this->validate([
            'minIncomeTarget' => 'required|numeric',
            'dayOfMonth' => 'required_unless:isEndOfMonth,true|between:0,31',
            'eachMonth' => 'required|numeric|between:1,12',
            'premTarget' => 'required|numeric',
            'commPercentage' => 'required|numeric|between:1,100',
            'maxIncomeTarget' => 'nullable|numeric|gt:minIncomeTarget',
            'addToBalance' => 'required|numeric|between:0,100',
            'addAsPayment' => 'required|numeric|between:0,100',
            'basePayment' => 'nullable|numeric',
            'isFullAmount' => 'nullable|boolean',
        ]);

        $res = $this->profile->addTarget(
            $this->isEndOfMonth ? 28 : $this->dayOfMonth,
            $this->eachMonth,
            $this->premTarget,
            $this->minIncomeTarget,
            $this->commPercentage,
            $this->addToBalance,
            $this->addAsPayment,
            $this->basePayment,
            $this->maxIncomeTarget,
            $this->nextRunDate ? new Carbon($this->nextRunDate) : null,
            $this->isEndOfMonth,
            $this->isFullAmount
        );

        if ($res) {
            $this->closeNewTargetSection();
            $this->mount($this->profile->id);
            $this->alert('success', 'target added!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function targetMoveup($id)
    {
        $res = Target::find($id)->moveUp();
        if ($res) {
            $this->mount($this->profile->id);
            $this->alert('success', 'target moved!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function targetMovedown($id)
    {
        $res = Target::find($id)->moveDown();
        if ($res) {
            $this->mount($this->profile->id);
            $this->alert('success', 'target moved!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function editThisConf($id)
    {
        $this->editConfId = $id;
        $c = CommProfileConf::find($id);
        $this->percentage = $c->percentage;
        $this->from = $c->from;
    }

    public function closeEditConf()
    {
        $this->editConfId = null;
        $this->percentage = null;
        $this->from = null;
    }

    public function editConf()
    {
        $this->validate([
            'percentage' => 'required|numeric',
            'from' => 'required|in:' . implode(',', CommProfileConf::FROMS),
        ]);

        $res = CommProfileConf::find($this->editConfId)->editInfo($this->percentage, $this->from);

        if ($res) {
            $this->closeEditConf();
            $this->mount($this->profile->id);
            $this->alert('success', 'Configuration updated!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function confirmDeleteConf($id)
    {
        $this->deleteConfId = $id;
    }

    public function dismissDeleteConf()
    {
        $this->deleteConfId = null;
    }

    public function deleteConf()
    {
        $res = CommProfileConf::find($this->deleteConfId)->deleteConfiguration();
        if ($res) {
            $this->deleteConfId = null;
            $this->mount($this->profile->id);
            $this->alert('success', 'Configuration deleted!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function moveup($id)
    {
        $res = CommProfileConf::find($id)->moveUp();
        if ($res) {
            $this->mount($this->profile->id);
            $this->alert('success', 'Configuration moved!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function movedown($id)
    {
        $res = CommProfileConf::find($id)->moveDown();
        if ($res) {
            $this->mount($this->profile->id);
            $this->alert('success', 'Configuration moved!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function updatedSearchCon()
    {
        if ($this->conditionType == 'company') {
            $this->searchlist = Company::searchBy($this->searchCon)->get()->take(5);
        } elseif ($this->conditionType == 'policy') {
            $this->searchlist = Policy::searchBy($this->searchCon)->get()->take(5);
        }
    }

    public function selectResult($id)
    {
        if ($this->conditionType == 'company') {
            $this->condition = Company::find($id);
        } elseif ($this->conditionType == 'policy') {
            $this->condition = Policy::find($id);
        }
    }

    public function addConf()
    {
        $this->validate([
            'percentage' => 'required|numeric',
            'from' => 'required|in:' . implode(',', CommProfileConf::FROMS),
        ]);
        if ($this->line_of_business) {
            $this->validate([
                'line_of_business' => 'required|in:' . implode(',', Policy::LINES_OF_BUSINESS),
            ]);
            $res = $this->profile->addConfiguration($this->percentage, $this->from, line_of_business: $this->line_of_business);
        } else {
            $res = $this->profile->addConfiguration($this->percentage, $this->from, condition: $this->condition);
        }

        if ($res) {
            $this->newConfSec = null;
            $this->percentage = null;
            $this->from = null;
            $this->condition = null;
            $this->line_of_business = null;
            $this->conditionType = 'company';
            $this->mount($this->profile->id);
            $this->alert('success', 'Configuration added!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function updatedConditionType()
    {
        $this->searchCon = null;
        $this->searchlist = null;
    }

    public function openNewConfSection()
    {
        $this->newConfSec = true;
    }

    public function closeNewConfSection()
    {
        $this->newConfSec = false;
    }

    public function openUpdateSec()
    {
        $this->updatedCommSec = true;
        $this->updatedType = $this->profile->type;
        $this->updatedPerPolicy = $this->profile->per_policy;
        // $this->updatedUserId = $this->profile->user_id;
        $this->updatedAutomaticOverrideId = $this->profile->auto_override_id;
        $this->updatedTitle = $this->profile->title;
        $this->updatedDesc = $this->profile->desc;
        $this->updatedSelectAvailable = $this->profile->select_available;
    }

    public function updateComm()
    {
        if ($this->profile->user) {
            $this->validate([
                'updatedTitle' => 'nullable|string|max:255',
            ]);
        } else {
            $this->validate([
                'updatedTitle' => 'required|string|max:255',
            ]);
        }
        $this->validate([
            'updatedType'  => 'required|in:' . implode(',', CommProfile::TYPES),
            'updatedAutomaticOverrideId'  => 'nullable|exists:comm_profiles,id',
            'updatedPerPolicy' => 'boolean',
            'updatedSelectAvailable' => 'boolean',
            'updatedDesc' => 'nullable|string'
        ]);

        $res = $this->profile->editProfile($this->updatedType, $this->updatedPerPolicy, $this->updatedTitle, $this->updatedDesc, $this->updatedSelectAvailable, $this->updatedAutomaticOverrideId);

        if ($res) {
            $this->updatedCommSec = false;
            $this->alert('success', 'Commission updated');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function closeUpdateSec()
    {
        $this->updatedCommSec = false;
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

    public function mount($id)
    {
        $this->profile = CommProfile::with('sales_comm', 'sales_comm.sold_policy', 'sales_comm.sold_policy.policy', 'sales_comm.sold_policy.policy.company')->find($id);
    }

    public function render()
    {
        $profileTypes = CommProfile::TYPES;
        $FROMS = CommProfileConf::FROMS;
        $users = User::all();
        $LOBs = Policy::LINES_OF_BUSINESS;
        $PYMT_TYPES = CommProfilePayment::PYMT_TYPES;
        $overrides = CommProfile::override()->get();
        return view('livewire.comm-profile-show', [
            'profileTypes' => $profileTypes,
            'users' => $users,
            'FROMS' => $FROMS,
            'LOBs' => $LOBs,
            'PYMT_TYPES' => $PYMT_TYPES,
            'overrides' => $overrides,
        ]);
    }
}
