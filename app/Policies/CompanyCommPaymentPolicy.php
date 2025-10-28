<?php

namespace App\Policies;

use App\Models\Payments\CompanyCommPayment;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyCommPaymentPolicy
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
     * @param  \App\Models\Payments\CompanyCommPayment  $companyCommPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CompanyCommPayment $companyCommPayment)
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
     * @param  \App\Models\Payments\CompanyCommPayment  $companyCommPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CompanyCommPayment $companyCommPayment)
    {
        return $user->is_admin || $user->is_finance;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CompanyCommPayment  $companyCommPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CompanyCommPayment $companyCommPayment)
    {
        return  ($companyCommPayment->is_new || $companyCommPayment->is_cancelled) && ($user->is_admin || $user->is_finance);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CompanyCommPayment  $companyCommPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CompanyCommPayment $companyCommPayment)
    {
        return $user->is_admin || $user->is_finance;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Payments\CompanyCommPayment  $companyCommPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CompanyCommPayment $companyCommPayment)
    {
        return $user->is_admin || $user->is_finance;
    }
}
