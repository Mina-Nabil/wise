<?php

namespace App\Policies;

use App\Models\Payments\CommProfilePayment;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommProfilePaymentPolicy
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
        return $user->is_finance || $user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CommProfilePayment  $commProfilePayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CommProfilePayment $commProfilePayment)
    {
        return $user->is_finance || $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->is_finance || $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CommProfilePayment  $commProfilePayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CommProfilePayment $commProfilePayment)
    {
        return $user->is_finance || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CommProfilePayment  $commProfilePayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CommProfilePayment $commProfilePayment)
    {
        return $user->is_finance || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CommProfilePayment  $commProfilePayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user, CommProfilePayment $commProfilePayment)
    {
        return $user->is_admin;
    }
}
