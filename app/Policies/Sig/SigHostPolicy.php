<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\SigHost;
use App\Models\User;

class SigHostPolicy extends ManageEventPolicy
{

    /**
     * Default abilities
     */

    public function viewAny(?User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::READ))
            return true;

        if(!TimetableEntryPolicy::isSchedulePublic())
            return false;

        return true;
    }

    public function view(?User $user, SigHost $sigHost): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::READ))
            return true;
        if(!TimetableEntryPolicy::isSchedulePublic())
            return false;

        if($sigHost->hide)
            return false;

        if($sigHost->reg_id === $user?->reg_id)
            return true;

        return true;
    }

    public function create(User $user, $sigHostRegId=null): bool {
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::WRITE))
            return true;
        // allow if new sigHost has reg_id of current user and isn't already registered as sigHost
        if($sigHostRegId == $user->reg_id && $user->sigHosts()->count() == 0)
            return true;

        return false;
    }

    public function update(User $user, SigHost $sigHost): bool {
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::WRITE))
            return true;
        if($sigHost->reg_id === $user->reg_id)
            return true;

        return false;
    }

    public function delete(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::DELETE))
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
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::WRITE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;
        return false;
    }

    public function attach(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::WRITE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;
        return false;
    }

    public function deleteAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::DELETE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::DELETE))
            return true;
        return false;
    }

    public function detach(User $user, SigHost $sigHost): bool {
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::WRITE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;
        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::WRITE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;
        return false;
    }

    public function disassociate(User $user, SigHost $sigHost): bool {
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::WRITE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;
        return false;
    }

    public function disassociateAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::WRITE))
            return true;
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

    public function replicate(User $user, SigHost $sigHost): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
