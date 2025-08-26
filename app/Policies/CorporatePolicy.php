<?php

namespace App\Policies;

use App\Models\Corporates\Corporate;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CorporatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Corporates\Corporate  $corporate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Corporate $corporate)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Customers\Customer  $customer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function exportAndImport(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Corporates\Corporate  $corporate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Corporate $corporate)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Corporates\Corporate  $corporate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Corporate $corporate)
    {
        return true;
    }
}
