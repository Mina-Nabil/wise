<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\CommProfile;
use App\Models\Users\User;
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
            $this->alert('success', 'Commission');
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
        $users = User::all();
        return view('livewire.comm-profile-show', [
            'profileTypes' => $profileTypes,
            'users' => $users,
        ]);
    }
}
