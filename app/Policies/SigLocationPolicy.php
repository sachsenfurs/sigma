<?php

namespace App\Policies;

use App\Models\SigLocation;
use App\Models\User;
use App\Settings\AppSettings;
use Illuminate\Auth\Access\HandlesAuthorization;

class SigLocationPolicy
{
    use HandlesAuthorization;

    /**
     * Overrides
     */
    public function before(?User $user): ?bool {
        if($user)
            if($user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data'))
                return true;

        return null;
    }


    /**
     * Default abilities
     */

    public function viewAny(?User $user): bool {
        if(!TimetableEntryPolicy::isSchedulePublic())
            return false;

        return true;
    }

    public function view(?User $user, SigLocation $sigLocation): bool {
        if(!TimetableEntryPolicy::isSchedulePublic())
            return false;

        return true;
    }


    public function create(User $user): bool {
        return false;
    }

    public function update(User $user, SigLocation $sigLocation): bool {
        return false;
    }

    public function delete(User $user): bool {
        return false;
    }

    public function restore(User $user): bool {
        return false;
    }

    public function forceDelete(User $user): bool {
        return false;
    }

    public function associate(User $user): bool {
        return false;
    }

    public function attach(User $user): bool {
        return false;
    }

    public function deleteAny(User $user): bool {
        return false;
    }

    public function detach(User $user, SigLocation $sigLocation): bool {
        return false;
    }

    public function detachAny(User $user): bool {
        return false;
    }

    public function disassociate(User $user, SigLocation $sigLocation): bool {
        return false;
    }

    public function disassociateAny(User $user): bool {
        return false;
    }

    public function forceDeleteAny(User $user): bool {
        return false;
    }

    public function reorder(User $user): bool {
        return false;
    }

    public function replicate(User $user, SigLocation $sigLocation): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
