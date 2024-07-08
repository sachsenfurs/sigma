<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\SigTag;
use App\Models\User;

class SigTagPolicy extends ManageEventPolicy
{

    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function view(User $user, SigTag $sigTag): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;


        return false;
    }

    public function create(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function update(User $user, SigTag $sigTag): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function delete(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::DELETE))
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

    public function detach(User $user, SigTag $sigTag): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, SigTag $sigTag): bool {
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

    public function replicate(User $user, SigTag $sigTag): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
