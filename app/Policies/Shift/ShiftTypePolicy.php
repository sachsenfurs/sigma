<?php

namespace App\Policies\Shift;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\User;

class ShiftTypePolicy extends ManageShiftPolicy
{

    public function view(User $user): bool {
        return $user->hasPermission(Permission::MANAGE_SHIFTS, PermissionLevel::READ);
    }

    public function viewAny(User $user): bool {
        return $user->hasPermission(Permission::MANAGE_SHIFTS, PermissionLevel::READ);
    }

    public function create(User $user): bool {
        return $user->hasPermission(Permission::MANAGE_SHIFTS, PermissionLevel::ADMIN);
    }

    public function update(): bool {
        return false;
    }

    public function updateAny(): bool {
        return false;
    }

    public function delete(): bool {
        return false;
    }

    public function deleteAny(): bool {
        return false;
    }

}
