<?php

namespace App\Policies;

use App\Models\Payments\ClientPayment;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPaymentPolicy
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
     * @param  \App\Models\Payments\ClientPayment  $clientPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ClientPayment $clientPayment)
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
        return $user->is_admin || $user->is_finance;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\ClientPayment  $clientPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ClientPayment $clientPayment)
    {
        $clientPayment->loadMissing('sold_policy');
        return $user->is_admin || $user->is_finance || $user->id == $clientPayment->sold_policy->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\ClientPayment  $clientPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ClientPayment $clientPayment)
    {
        return $user->is_admin || $user->is_finance;
    }
}