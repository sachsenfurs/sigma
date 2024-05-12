<?php

namespace App\Policies;

use App\Models\SigHost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SigHostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function view(User $user, SigHost $sigHost): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data')
            || $sigHost->reg_id === $user->reg_id;
    }

    public function create(User $user, $sigHostRegId=null): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data')
            ||  (
                // allow if new sigHost has reg_id of current user and isn't already registered as sigHost
                $sigHostRegId == $user->reg_id
                && $user->sigHosts()->count() == 0
            );
    }

    public function update(User $user, SigHost $sigHost): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data')
            || $sigHost->reg_id === $user->reg_id;
    }

    public function delete(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function restore(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function forceDelete(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function associate(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function attach(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function deleteAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function detach(User $user, SigHost $sigHost): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function detachAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function disassociate(User $user, SigHost $sigHost): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function disassociateAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function forceDeleteAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function reorder(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function replicate(User $user, SigHost $sigHost): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }

    public function restoreAny(User $user): bool {
        return $user->permissions()->contains('manage_events')
            || $user->permissions()->contains('manage_sig_base_data');
    }
}
