<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRole;

class UserRolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage_users');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserRole $userRole): bool
    {
        return $user->can('manage_users');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage_users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserRole $userRole): bool
    {
        return $user->can('manage_users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserRole $userRole): bool
    {
        return $user->can('manage_users');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserRole $userRole): bool
    {
        return $user->can('manage_users');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserRole $userRole): bool
    {
        return $user->can('manage_users');
    }

    public function associate(User $user): bool
    {
        return $user->can('manage_users');
    }

    public function attach(User $user): bool
    {
        return $user->can('manage_users');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('manage_users');
    }

    public function detach(User $user, UserRole $userRole): bool
    {
        return $user->can('manage_users');
    }

    public function detachAny(User $user): bool
    {
        return $user->can('manage_users');
    }

    public function disassociate(User $user, UserRole $userRole): bool
    {
        return $user->can('manage_users');
    }

    public function disassociateAny(User $user): bool
    {
        return $user->can('manage_users');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('manage_users');
    }

    public function reorder(User $user): bool
    {
        return $user->can('manage_users');
    }

    public function replicate(User $user, UserRole $userRole): bool
    {
        return $user->can('manage_users');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('manage_users');
    }
}
