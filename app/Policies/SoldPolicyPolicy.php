<?php

namespace App\Policies;

use App\Models\Business\SoldPolicy;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SoldPolicyPolicy
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
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SoldPolicy $soldPolicy)
    {
        return $user->is_admin || $user->is_operation || $user->id == $soldPolicy->creator_id;
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
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SoldPolicy $soldPolicy)
    {
        return $user->is_admin || $user->is_operation || $user->id == $soldPolicy->creator_id;
    }

    /**
     * Determine whether the user can update a sold policy payment's info
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updatePayments(User $user, SoldPolicy $soldPolicy)
    {
        return $user->is_admin ||  $user->is_finance || $user->id == 12;
    }

    /**
     * Determine whether the user can update a sold policy payment's info
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateClientPayments(User $user, SoldPolicy $soldPolicy)
    {
        return $user->is_admin ||  $user->is_finance || $user->id == $soldPolicy->creator_id || $user->id == 12;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SoldPolicy $soldPolicy)
    {
        return false;
    }
}
