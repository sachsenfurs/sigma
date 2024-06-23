<?php

namespace App\Policies\User;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\User;

class UserPolicy extends ManageUserPolicy
{

    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function view(User $user, User $model): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::READ))
            return true;

        if($model->id === $user->id)
            return true;

        return false;
    }

    public function create(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function update(User $user, User $model): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function delete(User $user, User $model): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function restore(User $user, User $model): bool {
        return false;
    }

    public function forceDelete(User $user, User $model): bool {
        return false;
    }

    public function associate(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function attach(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function deleteAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function detach(User $user, User $model): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, User $model): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociateAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_USERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function forceDeleteAny(User $user): bool {
        return false;
    }

    public function reorder(User $user): bool {
        return false;
    }

    public function replicate(User $user, User $model): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
