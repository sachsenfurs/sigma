<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\SigEvent;
use App\Models\User;
use App\Settings\AppSettings;

class SigEventPolicy extends ManageEventPolicy
{

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function view(?User $user, SigEvent $sigEvent): bool {
        // staff
        if($user AND $user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;

        // own events
        if($user AND $sigEvent->sigHosts->pluck("reg_id")->contains($user?->reg_id))
            return true;

        // not at least once in the public schedule?
        $entries = $sigEvent->timetableEntries;
        if($entries->count() == $entries->where("hide", 1)->count())
            return false;

        // at least once in the public schedule?
        return $entries->count() > 0;
    }

    public function create(User $user, $sigHostId = null): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;
        if(app(AppSettings::class)->sig_application_deadline->isBefore(now()) && !app(AppSettings::class)->accept_sigs_after_deadline)
            return false;
        if($user->sigHosts->contains($sigHostId))
            return true;

        return false;
    }

    public function update(User $user, SigEvent $sigEvent): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;
        if($sigEvent->sigHosts->pluck("reg_id")->contains($user->reg_id) && !$sigEvent->approved)
            return true;

        return false;
    }

    public function delete(User $user, SigEvent $sigEvent): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::DELETE))
            return true;
        if($sigEvent->sigHosts->pluck("reg_id")->contains($user->reg_id) && !$sigEvent->approved)
            return true;

        return false;
    }

    public function restore(User $user): bool {
        return false;
    }

    public function forceDelete(User $user): bool {
        return false;
    }

    public function associate(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function attach(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function deleteAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function detach(User $user, SigEvent $sigEvent): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, SigEvent $sigEvent): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociateAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function forceDeleteAny(User $user): bool {
        return false;
    }

    public function reorder(User $user): bool {
        return false;
    }

    public function replicate(User $user, SigEvent $sigEvent): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
