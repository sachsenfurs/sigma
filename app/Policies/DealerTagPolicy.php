<?php

namespace App\Policies;

use App\Models\DDAS\DealerTag;
use App\Models\User;

class DealerTagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DealerTag $dealerTag): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DealerTag $dealerTag): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DealerTag $dealerTag): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DealerTag $dealerTag): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DealerTag $dealerTag): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function associate(User $user): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function attach(User $user): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function deleteAny(User $user): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function detach(User $user, DealerTag $dealerTag): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function detachAny(User $user): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function disassociate(User $user, DealerTag $dealerTag): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function disassociateAny(User $user): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function reorder(User $user): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function replicate(User $user, DealerTag $dealerTag): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function restoreAny(User $user): bool
    {
        return $user->permissions()->contains('manage_dealers_den');
    }
}
