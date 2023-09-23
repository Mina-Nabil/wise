<?php

namespace App\Policies;

use App\Models\Cars\CarModel;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Response;

class CarModelPolicy
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
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CarModel $carModel)
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
        return Response::deny("Only admins can create models");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CarModel $carModel)
    {
        if ($user->is_admin) return true;
        return Response::deny("Only admins can delete models");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CarModel $carModel)
    {
        if ($user->is_admin) return true;
        return Response::deny("Only admins can restore models");
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CarModel $carModel)
    {
        if ($user->is_admin) return true;
        return Response::deny("Only admins can delete models");
    }
}
