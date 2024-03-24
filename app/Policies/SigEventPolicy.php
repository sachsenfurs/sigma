<?php

namespace App\Policies;

use App\Models\SigEvent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SigEventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SigEvent  $sigEvent
     * @return bool
     */
    public function view(User $user, SigEvent $sigEvent): bool
    {
        return $user->permissions()->contains('manage_events') || $sigEvent->sigHost->reg_id === $user->reg_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->permissions()->contains('manage_events');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SigEvent  $sigEvent
     * @return bool
     */
    public function update(User $user, SigEvent $sigEvent): bool
    {
         return $user->permissions()->contains('manage_events') || $sigEvent->sigHost->reg_id === $user->reg_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->permissions()->contains('manage_events');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SigEvent  $sigEvent
     * @return bool
     */
    public function restore(User $user): bool
    {
        return $user->permissions()->contains('manage_events');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SigEvent  $sigEvent
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $user->permissions()->contains('manage_events');
    }
}
