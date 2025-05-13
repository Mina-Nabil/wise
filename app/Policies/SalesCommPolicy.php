<?php

namespace App\Policies;

use App\Models\Payments\SalesComm;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesCommPolicy
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
        return $user->is_admin || $user->is_finance || $user->is_finance_assistant;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\SalesComm  $salesComm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SalesComm $salesComm)
    {
        return $user->is_admin || $user->is_finance || $user->is_finance_assistant;
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
     * @param  \App\Models\Payments\SalesComm  $salesComm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SalesComm $salesComm)
    {
        return $user->is_admin || $user->is_finance || $user->is_finance_assistant;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\SalesComm  $salesComm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SalesComm $salesComm)
    {
        return $user->is_admin || $user->is_finance;
    }

}
