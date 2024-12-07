<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\SigLocation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SigLocationPolicy extends ManageEventPolicy
{

    /**
     * Default abilities
     */

    public function viewAny(?User $user): bool {
        if($user?->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::READ))
            return true;
        if($user?->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::READ))
            return true;

        if(!TimetableEntryPolicy::isSchedulePublic())
            return false;

        return true;
    }

    public function view(?User $user, SigLocation $sigLocation): Response|bool {
        if($user?->hasPermission(Permission::MANAGE_HOSTS, PermissionLevel::READ))
            return true;
        if($user?->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::READ))
            return true;

        if(!TimetableEntryPolicy::isSchedulePublic())
            return false;

        if($sigLocation->timetableEntries->count() == 0)
            return Response::denyWithStatus(404);

        return true;
    }


    public function create(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function update(User $user, SigLocation $sigLocation): bool {
        if($user->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function delete(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::DELETE))
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
        if($user->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::WRITE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function attach(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::WRITE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function deleteAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::DELETE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function detach(User $user, SigLocation $sigLocation): bool {
        if($user->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::WRITE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::WRITE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, SigLocation $sigLocation): bool {
        if($user->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::WRITE))
            return true;
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociateAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_LOCATIONS, PermissionLevel::WRITE))
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

    public function replicate(User $user, SigLocation $sigLocation): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
