<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\CommProfile;
use App\Models\Users\User;
use App\Models\Payments\CommProfileConf;
use App\Models\Payments\Target;
use App\Models\Payments\TargetCycle;
use App\Models\Payments\CommProfilePayment;
use App\Models\Insurance\Policy;
use App\Models\Insurance\Company;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Aws\IoTThingsGraph\IoTThingsGraphClient;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class CommProfileShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire, WithFileUploads;
    public $profile;

    public $updatedCommSec = false;

    public $updatedType;
    public $updatedPerPolicy;
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
    public $period;
    public $amount;
    public $extra_percentage;
    public $deleteTargetId;
    public $editTargetId;

    public $newCycleSec;
    public $dayOfMonth;
    public $eachMonth;
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

    public $section = 'payments';

    protected $queryString = ['section'];

    public function changeSection($section)
    {
        $this->section = $section;
        $this->mount($this->profile->id);
    }

    public function closeEditPymtSection(){
        $this->pymtId = null;
        $this->pymtAmount = null;
        $this->pymtType = null;
        $this->pymtNote = null;
    }

    public function editThisPayment($id){
        $this->pymtId = $id;
        $p = CommProfilePayment::find($id);
        $this->pymtAmount = $p->amount;
        $this->pymtType = $p->type;
        $this->pymtNote = $p->note;
    }

    public function editPayment(){
        
        if (($this->pymtAmount) > ($this->profile->balance)) {
            throw ValidationException::withMessages([
                'pymtAmount' => 'Payment amount cannot exceed your balance.'
            ]);
        }

        $this->validate([
            'pymtAmount' => 'required|numeric|gt:0',
            'pymtType' => 'required|in:' . implode(',', CommProfilePayment::PYMT_TYPES),
            'pymtNote' => 'nullable|string'
        ]);

        $res = CommProfilePayment::find($this->pymtId)->setInfo($this->pymtAmount,$this->pymtType,$this->pymtNote);
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
                'pymtDocFile' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
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
        if (($this->pymtAmount) > ($this->profile->balance)) {
            throw ValidationException::withMessages([
                'pymtAmount' => 'Payment amount cannot exceed your balance.'
            ]);
        }

        $this->validate([
            'pymtAmount' => 'required|numeric|gt:0',
            'pymtType' => 'required|in:' . implode(',', CommProfilePayment::PYMT_TYPES),
            'pymtDoc' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
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

    public function closeEditCycle()
    {
        $this->editCycleId = null;
        $this->dayOfMonth = null;
        $this->eachMonth = null;
    }

    public function editCycle()
    {
        $this->validate([
            'dayOfMonth' => 'required|numeric|between:1,31',
            'eachMonth' => 'required|numeric'
        ]);
        $res = TargetCycle::find($this->editCycleId)->editInfo($this->dayOfMonth, $this->eachMonth);
        if ($res) {
            $this->closeEditCycle();
            $this->mount($this->profile->id);
            $this->alert('success', 'cycle updated!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function addCycle()
    {
        $this->validate([
            'dayOfMonth' => 'required|numeric|between:1,31',
            'eachMonth' => 'required|numeric'
        ]);
        $res = $this->profile->addTargetCycle($this->dayOfMonth, $this->eachMonth);
        if ($res) {
            $this->closeNewCycleSection();
            $this->mount($this->profile->id);
            $this->alert('success', 'cycle added!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function openNewCycleSection()
    {
        $this->newCycleSec = true;
    }

    public function closeNewCycleSection()
    {
        $this->newCycleSec = false;
        $this->dayOfMonth = null;
        $this->eachMonth = null;
    }

    public function editThisCycle($id)
    {
        $this->editCycleId = $id;
        $c = TargetCycle::find($this->editCycleId);
        $this->dayOfMonth = $c->day_of_month;
        $this->eachMonth = $c->each_month;
    }

    public function confirmDeleteCycle($id)
    {
        $this->deleteCycleId = $id;
    }

    public function deleteCycle()
    {
        $res = TargetCycle::find($this->deleteCycleId)->delete();
        if ($res) {
            $this->dismissDeleteCycle();
            $this->mount($this->profile->id);
            $this->alert('success', 'cycle deleted!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function dismissDeleteCycle()
    {
        $this->deleteCycleId = null;
    }

    public function closeNewTargetSection()
    {
        $this->newTargetSec = false;
    }

    public function openNewTargetSection()
    {
        $this->newTargetSec = true;
    }

    public function editThisTarget($id)
    {
        $this->editTargetId = $id;
        $t = Target::find($id);
        $this->period = $t->period;
        $this->amount = $t->amount;
        $this->extra_percentage = $t->extra_percentage;
    }

    public function closeEditTargetSection()
    {
        $this->editTargetId = null;
        $this->period = null;
        $this->amount = null;
        $this->extra_percentage = null;
    }

    public function editarget()
    {

        $this->validate([
            'period' => 'required|in:' . implode(',', Target::PERIODS),
            'amount' => 'required|numeric',
            'extra_percentage' => 'required|numeric',
        ]);

        $res = Target::find($this->editTargetId)->editInfo($this->period, $this->amount, $this->extra_percentage);
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
            'period' => 'required|in:' . implode(',', Target::PERIODS),
            'amount' => 'required|numeric',
            'extra_percentage' => 'required|numeric',
        ]);

        $res = $this->profile->addTarget($this->period, $this->amount, $this->extra_percentage);

        if ($res) {
            $this->closeNewTargetSection();
            $this->period = null;
            $this->amount = null;
            $this->extra_percentage = null;
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
        $this->updatedTitle = $this->profile->title;
        $this->updatedDesc = $this->profile->desc;
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
            'updatedPerPolicy' => 'boolean',
            'updatedDesc' => 'nullable|string'
        ]);

        $res = $this->profile->editProfile($this->updatedType, $this->updatedPerPolicy, $this->updatedTitle, $this->updatedDesc);

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

    public function mount($id)
    {
        $this->profile = CommProfile::find($id);
    }

    public function render()
    {
        $profileTypes = CommProfile::TYPES;
        $FROMS = CommProfileConf::FROMS;
        $users = User::all();
        $LOBs = Policy::LINES_OF_BUSINESS;
        $PERIODS = Target::PERIODS;
        $PYMT_TYPES = CommProfilePayment::PYMT_TYPES;
        return view('livewire.comm-profile-show', [
            'profileTypes' => $profileTypes,
            'users' => $users,
            'FROMS' => $FROMS,
            'LOBs' => $LOBs,
            'PERIODS' => $PERIODS,
            'PYMT_TYPES' => $PYMT_TYPES,
        ]);
    }
}
