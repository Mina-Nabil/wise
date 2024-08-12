<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payments\CommProfile;
use App\Models\Users\User;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommProfileIndex extends Component
{
    use WithPagination, AlertFrontEnd, ToggleSectionLivewire, AuthorizesRequests;

    public $newCommSec = false;
    public $search;

    public $newType;
    public $newPerPolicy = false;
    public $newSelectAvailable = false;
    public $newUserId;
    public $automaticOverrideId;
    public $newTitle;
    public $newDesc;

    public function redirectToShowPage($id)
    {
        redirect(route('comm.profile.show', $id));
    }

    public function addComm()
    {
        $this->authorize('create', CommProfile::class);
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
            'newSelectAvailable' => 'boolean',
            'automaticOverrideId'  => 'nullable|exists:comm_profiles,id',
            'newDesc' => 'nullable|string'
        ]);

        $res = CommProfile::newCommProfile($this->newType, $this->newPerPolicy, $this->newUserId, $this->newTitle, $this->newDesc, $this->newSelectAvailable, $this->automaticOverrideId);

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
        $profiles = CommProfile::when($this->search, fn($q) => $q->searchBy($this->search))->paginate(50);
        $profileTypes = CommProfile::TYPES;
        $users = User::all();
        $overrides = CommProfile::override()->get();

        return view('livewire.comm-profile-index', [
            'profileTypes' => $profileTypes,
            'profiles' => $profiles,
            'users' => $users,
            'overrides' => $overrides,
        ]);
    }
}
