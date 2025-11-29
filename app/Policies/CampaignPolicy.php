<?php

namespace App\Policies;

use App\Models\Marketing\Campaign;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
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
     * @param  \App\Models\Marketing\Campaign  $campaign
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Campaign $campaign)
    {
        return $user->is_admin;
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
     * @param  \App\Models\Marketing\Campaign  $campaign
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Campaign $campaign)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Marketing\Campaign  $campaign
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Campaign $campaign)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can import leads with a specific user_id.
     * Only admins can set a custom user_id, normal users will have leads assigned to themselves.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Marketing\Campaign  $campaign
     * @param  int|null  $user_id
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function importLeads(User $user, Campaign $campaign, ?int $user_id = null)
    {
        // If user_id is provided, only admins can use it
        if ($user_id !== null) {
            return $user->is_admin;
        }
        
        // Normal users can import leads (they'll be assigned to themselves)
        return true;
    }
}
