<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Str;

class UserObserver
{
    public function created(User $user): void {
        $this->createUpdate($user);
    }

    public function updated(User $user): void {
        $this->createUpdate($user);
    }

    private function createUpdate(User $user): void {
        $userRoles = UserRole::all()->pluck('registration_system_key', 'id')->map(function($item) {
            return $item != null ? explode(',', Str::lower($item)) : [];
        });

        $userGroups = $user->groups;
        if (!$userGroups)
            return;

        foreach ($userGroups as $userGroup) {
            if (!$userGroup)
                continue;

            $appliedUserRoles = $userRoles->where(function ($item) use ($userGroup) {
                return in_array($userGroup, $item);
            });

            $user->roles()->syncWithoutDetaching($appliedUserRoles->keys());
        }
    }

    public function deleted(User $user) {
        // deleting the polymorphic relationships which can't be handled by the DB
        $user->reminders()->delete();
        $user->notifications()->delete();
        $user->notificationRoutes()->delete();
    }
}
