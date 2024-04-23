<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\CommProfile;
use App\Models\Users\User;
use App\Models\Payments\CommProfileConf;
use App\Models\Payments\Target;
use App\Models\Payments\TargetCycle;
use App\Models\Insurance\Policy;
use App\Models\Insurance\Company;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;

class CommProfileShow extends Component
{
    use AlertFrontEnd ,ToggleSectionLivewire;
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

    public function closeEditCycle(){
        $this->editCycleId = null;
        $this->dayOfMonth = null;
        $this->eachMonth = null;
    }

    public function editCycle(){
        $this->validate([
            'dayOfMonth' => 'required|numeric|between:1,31',
            'eachMonth' => 'required|numeric'
        ]);
        $res = TargetCycle::find($this->editCycleId)->editInfo($this->dayOfMonth,$this->eachMonth);
        if ($res) {
            $this->closeEditCycle();
            $this->mount($this->profile->id);
            $this->alert('success', 'cycle updated!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function addCycle(){
        $this->validate([
            'dayOfMonth' => 'required|numeric|between:1,31',
            'eachMonth' => 'required|numeric'
        ]);
        $res = $this->profile->addTargetCycle($this->dayOfMonth,$this->eachMonth);
        if ($res) {
            $this->closeNewCycleSection();
            $this->mount($this->profile->id);
            $this->alert('success', 'cycle added!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function openNewCycleSection(){
        $this->newCycleSec = true;
    }

    public function closeNewCycleSection(){
        $this->newCycleSec = false;
        $this->dayOfMonth = null;
        $this->eachMonth = null;
    }

    public function editThisCycle($id){
        $this->editCycleId = $id;
        $c= TargetCycle::find($this->editCycleId);
        $this->dayOfMonth = $c->day_of_month;
        $this->eachMonth = $c->each_month;
    }

    public function confirmDeleteCycle($id){
        $this->deleteCycleId = $id;
    }

    public function deleteCycle(){
        $res = TargetCycle::find($this->deleteCycleId)->delete();
        if ($res) {
            $this->dismissDeleteCycle();
            $this->mount($this->profile->id);
            $this->alert('success', 'cycle deleted!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function dismissDeleteCycle(){
        $this->deleteCycleId = null;
    }

    public function closeNewTargetSection(){
        $this->newTargetSec = false;
    }

    public function openNewTargetSection(){
        $this->newTargetSec = true;
    }

    public function editThisTarget($id){
        $this->editTargetId = $id;
        $t = Target::find($id);
        $this->period = $t->period;
        $this->amount = $t->amount;
        $this->extra_percentage = $t->extra_percentage;
    }

    public function closeEditTargetSection(){
        $this->editTargetId = null;
        $this->period = null;
        $this->amount = null;
        $this->extra_percentage = null;
    }

    public function editarget(){

        $this->validate([
            'period' => 'required|in:' . implode(',', Target::PERIODS),
            'amount' => 'required|numeric',
            'extra_percentage' => 'required|numeric',
        ]);

        $res = Target::find($this->editTargetId)->editInfo($this->period,$this->amount,$this->extra_percentage);
        if ($res) {
            $this->closeEditTargetSection();
            $this->mount($this->profile->id);
            $this->alert('success', 'target updated!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function dismissDeleteTarget(){
        $this->deleteTargetId = null;
    }

    public function confirmDeleteTarget($id){
        $this->deleteTargetId = $id;
    }


    public function deleteTarget() {
        $res = Target::find($this->deleteTargetId)->deleteTarget();
        if ($res) {
            $this->dismissDeleteTarget();
            $this->mount($this->profile->id);
            $this->alert('success', 'target deleted!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function addTarget(){
        $this->validate([
            'period' => 'required|in:' . implode(',', Target::PERIODS),
            'amount' => 'required|numeric',
            'extra_percentage' => 'required|numeric',
        ]);
        
        $res = $this->profile->addTarget($this->period,$this->amount,$this->extra_percentage);

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

    public function targetMoveup($id)  {
        $res = Target::find($id)->moveUp();
        if ($res) {
            $this->mount($this->profile->id);
            $this->alert('success', 'target moved!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function targetMovedown($id)  {
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
        return view('livewire.comm-profile-show', [
            'profileTypes' => $profileTypes,
            'users' => $users,
            'FROMS' => $FROMS,
            'LOBs' => $LOBs,
            'PERIODS' => $PERIODS
        ]);
    }
}
