<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserRole;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $this->createUpdate($user);
    }

    public function updated(User $user)
    {
        $this->createUpdate($user);
    }

    private function createUpdate(User $user) {
        $userRoles = UserRole::all()->pluck('registration_system_key', 'id')->map(function($item) {
            return explode(',', $item);
        });

        $userGroups = $user->groups;
        foreach ($userGroups as $userGroup) {
            if (!$userGroup) {
                continue;
            }
            $userRole = $userRoles->search(function ($item) use ($userGroup) {
                return in_array($userGroup, $item);
            });
            if ($userRole) {
                $user->role()->associate($userRole);
                $user->saveQuietly(); // save() würde in nem endlos loop enden... hab ich gehört... >.>
            }
        }
    }

    public function deleted(User $user)
    {
        //
    }
}
