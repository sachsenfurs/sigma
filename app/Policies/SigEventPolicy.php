<?php

namespace App\Policies;

use App\Models\SigEvent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SigEventPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function view(User $user, SigEvent $sigEvent): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs')
            || $sigEvent->sigHosts->pluck("reg_id")->contains($user->reg_id);
    }

    public function create(User $user, $sigHostId = null): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs')
            || $user->sigHosts->contains($sigHostId);
    }

    public function update(User $user, SigEvent $sigEvent): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs')
            || $sigEvent->sigHosts->pluck("reg_id")->contains($user->reg_id);
    }

    public function delete(User $user, SigEvent $sigEvent): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs')
            || (
                $sigEvent->sigHosts->pluck("reg_id")->contains($user->reg_id)
                && !$sigEvent->approved
            );
    }

    public function restore(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function forceDelete(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function associate(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function attach(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function deleteAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function detach(User $user, SigEvent $sigEvent): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function detachAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function disassociate(User $user, SigEvent $sigEvent): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function disassociateAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function forceDeleteAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function reorder(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function replicate(User $user, SigEvent $sigEvent): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }

    public function restoreAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sigs');
    }
}
