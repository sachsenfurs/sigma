<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\SigForm;
use App\Models\User;

class SigFormPolicy extends ManageEventPolicy
{

    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function view(User $user, SigForm $sigForm): bool {
        return true;
    }

    public function create(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function update(User $user, SigForm $sigForm): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;
        if($sigForm->form_closed)
            return false;

        return false;
    }

    public function delete(User $user, SigForm $sigForm): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::DELETE))
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
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function attach(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function deleteAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detach(User $user, SigForm $sigForm): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, SigForm $sigForm): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociateAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function forceDeleteAny(User $user): bool {
        return false;
    }

    public function reorder(User $user): bool {
        return false;
    }

    public function replicate(User $user, SigForm $sigForm): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
