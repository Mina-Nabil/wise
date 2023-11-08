<?php

namespace App\Policies;

use App\Models\Users\AppLog;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppLogPolicy
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
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Users\AppLog  $appLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AppLog $appLog)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Users\AppLog  $appLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AppLog $appLog)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Users\AppLog  $appLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AppLog $appLog)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Users\AppLog  $appLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AppLog $appLog)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Users\AppLog  $appLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AppLog $appLog)
    {
        //
    }
}
