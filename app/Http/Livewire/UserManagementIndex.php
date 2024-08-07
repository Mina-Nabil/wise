<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Users\User;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UserManagementIndex extends Component
{
    use WithPagination, AlertFrontEnd, WithFileUploads;

    public $newUserSection;
    public $search;

    public $newUsername;
    public $newFirstName;
    public $newLastName;
    public $newType;
    public $newPassword;
    public $newPassword_confirmation;
    public $newEmail;
    public $newPhone;
    public $newManagerId;

    public $updateUserSec;
    public $username;
    public $userImage;
    public $first_name;
    public $last_name;
    public $type;
    public $email;
    public $phone;
    public $password;
    public $user;

    public function clearImage()
    {
        $this->userImage = null;
    }

    function generateUrl()
    {
        $url = null;
        $user = User::find($this->updateUserSec);
        if (is_null($user->image) && is_null($this->userImage)) {
            $url = null;
        } elseif (!is_null($user->image) && is_null($this->userImage)) {
            $url = null;
        } elseif (!is_null($user->image) && !is_null($this->userImage)) {
            if (is_string($this->userImage)) {
                $this->userImage = null;
                $url = $user->image;
            }
        } elseif (is_null($user->image) && !is_null($this->userImage)) {
            $this->validate([
                'userImage' => 'image|mimes:jpeg,jpg,png|max:1024', // Adjust max size as needed
            ]);
            $url = $this->userImage->store(User::FILES_DIRECTORY, 's3');
        }

        return $url;
    }

    public function updateThisUser($id)
    {
        $this->updateUserSec = $id;
        $user = User::find($id);
        $this->user = $user;
        $this->username = $user->username;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->type = $user->type;
        $this->email = $user->email;
        $this->phone = $user->phone;
        if ($user->image) {
            $this->userImage = Storage::disk('s3')->url(str_replace('//', '/', $user->image));
        }
    }

    public function toggleUserStatus($id)
    {
        $res = User::find($id)->toggle();
        if ($res) {
            $this->alert('success', 'User updated successfuly!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function closeUpdateThisUser()
    {
        $this->reset(['updateUserSec', 'username', 'first_name', 'last_name', 'type', 'email', 'phone', 'userImage']);
    }

    public function EditUser()
    {
        $currentUserId = $this->updateUserSec;
        $this->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($currentUserId) {
                    $exists = User::where('username', $value)->where('id', '!=', $currentUserId)->exists();

                    if ($exists) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'type' => 'nullable|in:' . implode(',', User::TYPES),
            'email' => [
                'nullable',
                'email',
                function ($attribute, $value, $fail) use ($currentUserId) {
                    $exists = User::where('email', $value)->where('id', '!=', $currentUserId)->exists();

                    if ($exists) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            'phone' => 'nullable|numeric',
        ]);

        $imageUrl = $this->generateUrl();

        $res = User::find($currentUserId)->editInfo($this->username, $this->first_name, $this->last_name, $this->type, $this->email, $this->phone, $imageUrl, $this->password);
        if ($res) {
            $this->closeUpdateThisUser();
            $this->alert('success', 'User updated successfuly!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function closeNewUserSec()
    {
        $this->newUserSection = false;
        $this->reset(['newUsername', 'newFirstName', 'newLastName', 'newType', 'newPassword', 'newPassword_confirmation', 'newEmail', 'newPhone', 'newManagerId']);
    }

    protected $rules = [
        'newUsername' => 'required|string|max:255|unique:users,username',
    ];

    public function updatedNewUsername()
    {
        $this->validateOnly('newUsername');
    }

    public function addNewUser()
    {
        $validatedData = $this->validate([
            'newUsername' => 'required|string|max:255|unique:users,username',
            'newFirstName' => 'required|string|max:255',
            'newLastName' => 'required|string|max:255',
            'newType' => 'nullable|in:' . implode(',', User::TYPES),
            'newPassword' => 'required|string|min:8|confirmed',
            'newEmail' => 'nullable|email|unique:users,email',
            'newPhone' => 'nullable|numeric',
            'newManagerId' => 'nullable|exists:users,id',
        ]);

        $res = User::newUser($this->newUsername, $this->newFirstName, $this->newLastName, $this->newType, $this->newPassword, $this->newEmail, $this->newPhone, $this->newManagerId);

        if ($res) {
            $this->closeNewUserSec();
            $this->alert('success', 'User added successfuly!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function openNewUserSec()
    {
        $this->newUserSection = true;
    }

    public function render()
    {
        $TYPES = User::TYPES;
        $users = User::when($this->search, fn ($q) => $q->search($this->search))->paginate(50);
        return view('livewire.user-management-index', [
            'users' => $users,
            'TYPES' => $TYPES,
        ]);
    }
}
