<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\SigTimeslot;
use App\Models\User;

class SigTimeslotPolicy
{
    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function delete(SigTimeslot $sigTimeslot) {

    }
}
