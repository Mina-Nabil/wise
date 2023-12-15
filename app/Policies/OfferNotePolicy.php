<?php

namespace App\Policies;

use App\Models\Offers\OfferNote;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferNotePolicy
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
     * @param  \App\Models\Offers\OfferNote  $offerNote
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, OfferNote $offerNote)
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
     * @param  \App\Models\Offers\OfferNote  $offerNote
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, OfferNote $offerNote)
    {
        return $user->id == $offerNote->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Offers\OfferNote  $offerNote
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, OfferNote $offerNote)
    {
        return $user->id == $offerNote->user_id;
    }

}
