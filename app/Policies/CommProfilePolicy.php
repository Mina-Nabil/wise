<?php

namespace App\Policies;

use App\Models\Payments\CommProfile;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommProfilePolicy
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
        return $user->is_admin || $user->is_finance;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CommProfil  $commProfil
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CommProfile $commProfil)
    {
        return $user->is_admin || $user->is_finance;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CommProfil  $commProfil
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CommProfile $commProfil)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CommProfil  $commProfil
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manage(User $user, CommProfile $commProfil)
    {
        return $user->is_admin || $user->is_finance;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CommProfil  $commProfil
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CommProfile $commProfil)
    {
        return $user->is_admin;
    }
}
