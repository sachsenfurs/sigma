<?php

namespace App\Policies;

use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimetableEntryPolicy
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
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param TimetableEntry $timetableEntry
     * @return bool
     */
    public function view(User $user, TimetableEntry $timetableEntry): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param TimetableEntry $timetableEntry
     * @return bool
     */
    public function update(User $user, TimetableEntry $timetableEntry): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @return bool
     */
    public function restore(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function associate(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function attach(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function deleteAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function detach(User $user, TimetableEntry $timetableEntry): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function detachAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function disassociate(User $user, TimetableEntry $timetableEntry): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function disassociateAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function reorder(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function replicate(User $user, TimetableEntry $timetableEntry): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }

    public function restoreAny(User $user): bool
    {
        return $user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs');
    }
}
