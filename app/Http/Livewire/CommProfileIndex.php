<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\CommProfile;
use App\Models\Users\User;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;

class CommProfileIndex extends Component
{
    use WithPagination, AlertFrontEnd, ToggleSectionLivewire;

    public $newCommSec = false;

    public $newType;
    public $newPerPolicy = false;
    public $newUserId;
    public $newTitle;
    public $newDesc;

    public function addComm()
    {
        if ($this->newUserId) {
            $this->validate([
                'newUserId' => 'required|integer|exists:users,id',
            ]);
        } else {
            $this->validate([
                'newTitle' => 'required|string|max:255',
            ]);
        }
        $this->validate([
            'newType'  => 'required|in:' . implode(',', CommProfile::TYPES),
            'newPerPolicy' => 'boolean',
            'newDesc' => 'nullable|string'
        ]);

        $res = CommProfile::newCommProfile($this->newType, $this->newPerPolicy, $this->newUserId, $this->newTitle, $this->newDesc);

        if ($res) {
            // $this->alert('success', 'Commission added');
            redirect()->route('comm.profile.show', $res->id);
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function toggleNewCommSec()
    {
        $this->toggle($this->newCommSec);
    }

    public function render()
    {
        $profiles = CommProfile::paginate(50);
        $profileTypes = CommProfile::TYPES;
        $users = User::all();
        return view('livewire.comm-profile-index', [
            'profileTypes' => $profileTypes,
            'profiles' => $profiles,
            'users' => $users,
        ]);
    }
}
