<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\CommProfile;
use App\Models\Users\User;
use App\Models\Payments\CommProfileConf;
use App\Models\Insurance\Policy;
use App\Models\Insurance\Company;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;

class CommProfileShow extends Component
{
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
        return view('livewire.comm-profile-show', [
            'profileTypes' => $profileTypes,
            'users' => $users,
            'FROMS' => $FROMS,
            'LOBs' => $LOBs
        ]);
    }
}
