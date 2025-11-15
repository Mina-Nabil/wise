<?php

namespace App\Policies;

use App\Models\Marketing\Review;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
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
        return $user->is_admin || $user->is_crm || $user->can_review_reviews;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Marketing\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Review $review)
    {
        return $user->is_admin || $user->is_crm;
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
     * @param  \App\Models\Marketing\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Review $review)
    {
        return $user->is_admin || $user->is_crm;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Marketing\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Review $review)
    {
        return $user->is_admin || $user->is_crm;
    }

    public function markAsReviewed(User $user, Review $review)
    {
        return $user->is_admin || $user->can_review_reviews;
    }

    public function receiveClientComment(User $user, Review $review)
    {
        return $user->is_admin || $user->is_crm;
    }
}
