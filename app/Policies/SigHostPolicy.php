<?php

namespace App\Policies;

use App\Models\SigHost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SigHostPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool {
        if($user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sig_base_data'))
            return true;

        return null;
    }

    public function viewAny(User $user): bool {
        return false;
    }

    public function view(User $user, SigHost $sigHost): bool {
        if($sigHost->hide)
            return false;

        if($sigHost->reg_id === $user->reg_id)
            return true;

        return false;
    }

    public function create(User $user, $sigHostRegId=null): bool {
        // allow if new sigHost has reg_id of current user and isn't already registered as sigHost
        if($sigHostRegId == $user->reg_id && $user->sigHosts()->count() == 0)
            return true;

        return false;
    }

    public function update(User $user, SigHost $sigHost): bool {
        if($sigHost->reg_id === $user->reg_id)
            return true;

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

    public function detach(User $user, SigHost $sigHost): bool {
        return false;
    }

    public function detachAny(User $user): bool {
        return false;
    }

    public function disassociate(User $user, SigHost $sigHost): bool {
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

    public function replicate(User $user, SigHost $sigHost): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
