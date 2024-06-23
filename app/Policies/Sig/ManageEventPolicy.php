<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ManageEventPolicy
{

    /**
     * Overrides
     */

    public function before(User $user): bool|null|Response {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::ADMIN))
            return true;

        return null;
    }
}
