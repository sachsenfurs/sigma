<?php

namespace App\Policies;

use App\Models\SigForm;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SigFormPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param SigForm $sigForm
     * @return bool
     */
    public function view(User $user, SigForm $sigForm): bool
    {
        return $user->permissions()->contains('manage_forms') || $sigForm->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param SigForm $sigForm
     * @return bool
     */
    public function update(User $user, SigForm $sigForm): bool
    {
        return $user->permissions()->contains('manage_forms') || $sigForm->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param SigForm $sigForm
     * @return bool
     */
    public function delete(User $user, SigForm $sigForm): bool
    {
        return $user->permissions()->contains('manage_forms') || $sigForm->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @return bool
     */
    public function restore(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function associate(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function attach(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function deleteAny(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function detach(User $user, SigForm $sigForm): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function detachAny(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function disassociate(User $user, SigForm $sigForm): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function disassociateAny(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function reorder(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function replicate(User $user, SigForm $sigForm): bool
    {
        return $user->permissions()->contains('manage_forms');
    }

    public function restoreAny(User $user): bool
    {
        return $user->permissions()->contains('manage_forms');
    }
}
