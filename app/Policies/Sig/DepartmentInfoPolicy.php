<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\DepartmentInfo;
use App\Models\User;

class DepartmentInfoPolicy extends ManageEventPolicy
{

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function view(User $user, DepartmentInfo $departmentInfo): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;
        if($departmentInfo->sigEvent->sigHost->reg_id === $user->reg_id)
            return true;

        return false;
    }

    public function create(User $user, $sigHostId = null): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;
        if($user->sigHosts->contains($sigHostId))
            return true;

        return false;
    }

    public function update(User $user, DepartmentInfo $departmentInfo): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;
        if($departmentInfo->sigEvent->sigHost->reg_id === $user->reg_id && !$departmentInfo->sigEvent->approved)
            return true;

        return false;
    }

    public function delete(User $user, DepartmentInfo $departmentInfo): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::DELETE))
            return true;
        if($departmentInfo->sigEvent->sigHost->reg_id == $user->reg_id && !$departmentInfo->sigEvent->approved)
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

    public function detach(User $user, DepartmentInfo $departmentInfo): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, DepartmentInfo $departmentInfo): bool {
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

    public function replicate(User $user, DepartmentInfo $departmentInfo): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
