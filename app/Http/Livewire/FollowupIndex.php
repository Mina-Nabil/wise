<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customers\Followup;
use App\Traits\AlertFrontEnd;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;

class FollowupIndex extends Component
{
    use WithPagination, AlertFrontEnd, ToggleSectionLivewire;

    public $search;

    //followups
    public $addFollowupSection = false;
    public $followupTitle;
    public $followupCallDate;
    public $followupCallTime;
    public $followupDesc;
    public $followupId;
    public $deleteFollowupId;

    public $callerNoteSec = false;

    public $callerNotetype;
    public $callerNoteId;
    public $note;

    public function toggleCallerNote($type = null, $id = null)
    {

        $this->callerNotetype = $type;
        $this->callerNoteId = $id;
        $this->toggle($this->callerNoteSec);
    }

    public function submitCallerNote()
    {

        if ($this->callerNotetype === 'called') {
            $this->setFollowupAsCalled($this->callerNoteId, $this->note);
        } elseif ($this->callerNotetype == 'cancelled') {
            $this->setFollowupAsCancelled($this->callerNoteId, $this->note);
        }
    }

    public function redirectToShowPage($id)
    {
        $followup = Followup::findOrFail($id);
        return redirect(route($followup->called_type . 's.show',  $followup->called_id));
    }

    public function redirectToCalledPage($id)
    {
        $followup = Followup::findOrFail($id);
        return redirect(route($followup->called_type . 's.show',  $followup->called_id));
    }

    public function setFollowupAsCalled($id)
    {
        $res = Followup::find($id)->setAsCalled($this->note);
        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->toggleCallerNote();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setFollowupAsCancelled($id)
    {
        $res = Followup::find($id)->setAsCancelled($this->note);
        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->toggleCallerNote();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function closeEditFollowup()
    {
        $this->followupId = null;
        $this->followupTitle = null;
        $this->followupCallDate = null;
        $this->followupCallTime = null;
        $this->followupDesc = null;
    }

    public function editThisFollowup($id)
    {
        $this->followupId = $id;
        $f = Followup::find($id);
        $this->followupTitle = $f->title;
        $combinedDateTime = new \DateTime($f->call_time);
        $this->followupCallDate = $combinedDateTime->format('Y-m-d');
        $this->followupCallTime = $combinedDateTime->format('H:i:s');
        $this->followupDesc = $f->desc;
    }

    public function editFollowup()
    {
        $this->validate([
            'followupTitle' => 'required|string|max:255',
            'followupCallDate' => 'nullable|date',
            'followupCallTime' => 'nullable',
            'followupDesc' => 'nullable|string|max:255'
        ]);

        $combinedDateTimeString = $this->followupCallDate . ' ' . $this->followupCallTime;
        $combinedDateTime = new \DateTime($combinedDateTimeString);

        $followup = Followup::find($this->followupId);

        $res = $followup->editInfo(
            $this->followupTitle,
            $combinedDateTime,
            $this->followupDesc
        );

        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->closeEditFollowup();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    //reseting page while searching
    public function updatingSearchText()
    {
        $this->resetPage();
    }


    public function render()
    {
        $followups = Followup::userData($this->search)->paginate(10);
        return view('livewire.followup-index', [
            'followups' => $followups,
        ]);
    }
}
