<?php

namespace App\Observers;

use App\Models\UserRole;

class UserRoleObserver
{

    public function deleting(UserRole $userRole) {
        return $userRole->id != 1;
    }

    public function updating(UserRole $userRole) {
        return $userRole->id != 1;
    }
}
