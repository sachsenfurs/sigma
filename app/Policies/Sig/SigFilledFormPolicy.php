<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\SigFilledForm;
use App\Models\SigForm;
use App\Models\User;

class SigFilledFormPolicy extends ManageEventPolicy
{
    public function viewAny(User $user): bool {
        return false;
    }


    public function view(User $user, SigFilledForm $sigFilledForm): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::READ))
            return true;

        if($sigFilledForm->user_id === $user->id)
            return true;

        return false;
    }

    public function create(User $user, SigForm $form): bool {
        if($form->form_closed)
            return false;

        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function update(User $user, SigFilledForm $sigFilledForm): bool {
        if($sigFilledForm->sigForm->form_closed)
            return false;
        if($sigFilledForm->user_id === $user->id)
            return true;

        return false;
    }

    public function delete(User $user, SigFilledForm $sigFilledForm): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::DELETE))
            return true;

        if($sigFilledForm->user_id === $user->id)
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
        return false;
    }

    public function detach(User $user, SigFilledForm $sigFilledForm): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_FORMS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, SigFilledForm $sigFilledForm): bool {
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

    public function replicate(User $user, SigFilledForm $sigFilledForm): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
