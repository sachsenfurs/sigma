<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\SigTimeslot;
use App\Models\User;

class SigTimeslotPolicy extends ManageEventPolicy
{
    public function create(User $user, ): bool {
        return $user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE);
    }

    public function view(User $user, SigTimeslot $sigTimeslot=null): bool {
        return $user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ);
    }

    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function delete(User $user): bool {
        return $user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE);
    }

}
