<?php

namespace App\Policies\Shift;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Shift;
use App\Models\ShiftType;
use App\Models\User;

class ShiftPolicy extends ManageShiftPolicy
{
    public function create(User $user, ?ShiftType $shiftType=null): bool {
        if($shiftType AND $user->roles->contains($shiftType->user_role_id) AND $user->hasPermission(Permission::MANAGE_SHIFTS, PermissionLevel::DELETE))
            return true;
        return false;
    }

    public function update(User $user, Shift $shift): bool {
        if($shift->locked AND !$user->hasPermission(Permission::MANAGE_SHIFTS, PermissionLevel::DELETE))
            return false;

        // own department
        if($user->roles->contains($shift->type->user_role_id))
            if($user->hasPermission(Permission::MANAGE_SHIFTS, PermissionLevel::WRITE))
                return true;

        return false;
    }

    public function delete(User $user, Shift $shift): bool {
        return $user->roles->contains($shift->type->user_role_id) AND $user->hasPermission(Permission::MANAGE_SHIFTS, PermissionLevel::DELETE);
    }

    public function deleteAny(User $user): bool {
        return $user->hasPermission(Permission::MANAGE_SHIFTS, PermissionLevel::DELETE);
    }
}
