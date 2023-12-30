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

    public function redirectToShowPage($id)
    {
        $followup = Followup::findOrFail($id);
        return redirect(route($followup->called_type.'s.show',  $followup->called_id));
    }

    public function closeEditFollowup()
    {
        $this->followupId = null;
        $this->followupTitle = null;
        $this->followupCallDate = null;
        $this->followupCallTime = null;
        $this->followupDesc = null;
    }

    public function editThisFollowup($id){
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

    public function render()
    {
        $followups = Followup::userData($this->search)->paginate(10);
        return view('livewire.followup-index',[
            'followups' => $followups,
        ]);
    }
}
