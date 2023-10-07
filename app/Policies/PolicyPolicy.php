<?php

namespace App\Policies;

use App\Models\Insurance\Policy;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PolicyPolicy
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
     * @param  \App\Models\Insurance\Policy  $policy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Policy $policy)
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
        if ($user->is_admin) return true;
        return Response::deny("Only admins can create policy");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Insurance\Policy  $policy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Policy $policy)
    {
        if ($user->is_admin) return true;
        return Response::deny("Only admins can update policy");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Insurance\Policy  $policy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Policy $policy)
    {
        if ($user->is_admin) return true;
        return Response::deny("Only admins can delete policy");
    }

}
