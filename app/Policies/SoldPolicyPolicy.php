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
        return $user->is_admin || $user->is_operations || $user->id == $soldPolicy->creator_id;
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
        return $user->is_admin || $user->is_operations || $user->is_any_finance;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateClaim(User $user, SoldPolicy $soldPolicy)
    {
        return $user->is_admin || $user->is_operations;
    }

    /**
     * Determine whether the user can update a sold policy payment's info
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewCommission(User $user, SoldPolicy $soldPolicy = null)
    {
        return $this->viewFinanceWhileReview($user) && ($user->is_admin ||  $user->is_any_finance);
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
        return $user->is_admin || $user->is_finance || $user->id == 12 || $user->id == $soldPolicy->main_sales_id;
    }

    /**
     * Determine whether the user can update a sold policy penalty info
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updatePenalty(User $user, SoldPolicy $soldPolicy)
    {
        return $user->is_admin || $user->is_finance;
    }

    /**
     * Determine whether the user can review a sold policy
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function review(User $user, SoldPolicy $soldPolicy = null)
    {
        return $user->is_admin || $user->is_operations;
    }

    /**
     * Determine whether the user can update a sold policy payment's info
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateWiseCommPayments(User $user, SoldPolicy $soldPolicy)
    {
        return $user->id == 1 || $user->id == 10 || $user->id == 11; //remon w mina N. w michael 
    }

    /**
     * Determine whether the user can update a sold policy payment's info
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateClientPaymentDate(User $user, SoldPolicy $soldPolicy)
    {
        return $user->id == 1 || $user->id == 10 || $user->id == 11; //remon w mina N. w michael 
    }
    /**
     * Determine whether the user can update a sold policy's main sales
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Business\SoldPolicy  $soldPolicy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateMainSales(User $user, SoldPolicy $soldPolicy)
    {
        return $user->id == 1 || $user->id == 10 || $user->id == 11; //remon w mina N. w michael 
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
        return $user->is_admin ||  $user->is_any_finance || $user->id == $soldPolicy->creator_id || $user->id == 12 || $user->id == $soldPolicy->main_sales_id;
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
        return $user->id == 1 || $user->id == 10;
    }

    public function viewFinanceWhileReview(User $user, SoldPolicy $soldPolicy = null)
    {
        return $user->is_admin;
    }
}
