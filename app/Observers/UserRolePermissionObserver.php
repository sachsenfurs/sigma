<?php

namespace App\Observers;

use App\Enums\Permission;
use App\Models\UserRolePermission;

class UserRolePermissionObserver
{
    public function deleting(UserRolePermission $userRolePermission): bool {
        return $this->updating($userRolePermission);
    }

    public function updating(UserRolePermission $userRolePermission): bool {
        return !($userRolePermission->user_role_id == 1 AND $userRolePermission->permission == Permission::MANAGE_ADMIN);
    }
}
