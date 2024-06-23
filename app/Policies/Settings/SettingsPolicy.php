<?php

namespace App\Policies\Settings;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\User;

class SettingsPolicy
{
    public function appSettings(User $user): bool {
        return $user->hasPermission(Permission::MANAGE_SETTINGS, PermissionLevel::ADMIN);
    }

    public function artshowSettings(User $user): bool {
        return $user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::ADMIN);
    }

    public function dealerSettings(User $user): bool {
        return $user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::ADMIN);
    }
}
