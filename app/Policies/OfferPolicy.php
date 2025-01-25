<?php

namespace App\Policies;

use App\Models\Offers\Offer;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
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
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Offer $offer)
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
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Offer $offer)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateAssignTo(User $user, Offer $offer)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateStatus(User $user, Offer $offer)
    {
        return true;
    }
    public function setInsuranceStatuses(User $user, Offer $offer)
    {
        return $user->is_operations;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateItem(User $user, Offer $offer)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateLineFields(User $user, Offer $offer)
    {
        return true;
    }

      /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateOptions(User $user, Offer $offer)
    {
        return true;
    }

      /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateDiscount(User $user, Offer $offer)
    {
        return true;
    }

      /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateFlag(User $user, Offer $offer)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateDue(User $user, Offer $offer)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateNote(User $user, Offer $offer)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateCommission(User $user, Offer $offer)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\Offer  $offer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Offer $offer)
    {
        return true;
    }
}
