<?php

namespace App\Policies;

use App\Models\SigTag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SigTagPolicy
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
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param SigTag $sigTag
     * @return bool
     */
    public function view(User $user, SigTag $sigTag): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param SigTag $sigTag
     * @return bool
     */
    public function update(User $user, SigTag $sigTag): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @return bool
     */
    public function restore(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function associate(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function attach(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function deleteAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function detach(User $user, SigTag $sigTag): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function detachAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function disassociate(User $user, SigTag $sigTag): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function disassociateAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function reorder(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function replicate(User $user, SigTag $sigTag): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }

    public function restoreAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data');
    }
}
