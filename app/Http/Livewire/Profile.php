<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Traits\AlertFrontEnd;

class Profile extends Component
{
    use AlertFrontEnd;

    public $username;
    public $firstName;
    public $lastName;
    public $phone;
    public $email;

    public $changes;

    public $currentPassword;
    public $newPassword;
    public $password_confirmation;

    public function mount()
    {
        $this->username = auth()->user()->username;
        $this->firstName = auth()->user()->first_name;
        $this->lastName = auth()->user()->last_name;
        $this->phone = auth()->user()->phone;
        $this->email = auth()->user()->email;
    }

    public function updatingUsername()
    {
        $this->changes = true;
    }

    public function updatingFirstName()
    {
        $this->changes = true;
    }

    public function updatingLastName()
    {
        $this->changes = true;
    }

    public function updatingPhone()
    {
        $this->changes = true;
    }

    public function updatingEmail()
    {
        $this->changes = true;
    }

    public function saveInfo()
    {
        $this->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore(auth()->user()->id)],
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        $user = User::find(auth()->user()->id);

        $u = $user->editInfo(
            $this->username,
            $this->firstName,
            $this->lastName,
            auth()->user()->type, // Assuming 'type' is a property of the currently authenticated user
            $this->email,
            $this->phone,
        );

        if ($u) {
            $this->alert('success', 'Updated Successfuly');
            $this->changes = false;
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function changePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|string|min:6',
        ]);

        // Get the authenticated user
        /** @var User */
        $user = Auth::user();
        if($user == null) return $this->alert('failed', 'Unauthorized access');
        
        // Check if the entered current password matches the user's actual password
        if (Hash::check($this->currentPassword, $user->password)) {
            // Current password is correct
            // Proceed to update the password
            $user->changePassword($this->newPassword);
            $this->alert('success', 'Updated Successfuly');
        } else {
            // Current password is incorrect
            $this->alert('failed', 'Incorrect password!');
        }
    }

    public function render()
    {
        $users = User::all();
        return view('livewire.profile', [
            'users' => $users,
        ]);
    }
}
