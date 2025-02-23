<?php

namespace App\Observers;

use App\Models\UserRole;

class UserRoleObserver
{

    public function deleting(UserRole $userRole): bool {
        return $userRole->id != 1;
    }

    public function deleted(UserRole $userRole): void {
        $userRole->notificationRoutes()->delete();
        $userRole->reminders()->delete();
    }
}
