<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Users\User;
use Livewire\WithPagination;

class UserManagementIndex extends Component
{
    use WithPagination;

    public $newUserSection;

    public function closeNewUserSec(){
        $this->newUserSection = false;
    }

    public function render()
    {
        $users = User::paginate(50);
        return view('livewire.user-management-index',[
            'users' => $users
        ]);
    }
}
