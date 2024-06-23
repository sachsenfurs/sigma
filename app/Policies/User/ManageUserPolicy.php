<?php

namespace App\Policies\User;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ManageUserPolicy
{

    /**
     * Overrides
     */

    public function before(User $user): bool|null|Response {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::ADMIN))
            return true;

        return null;
    }
}
