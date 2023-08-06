<?php

namespace App\Observers;

use App\Models\User;

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
        // assign user role to specific groups
        if(in_array("leitstelle", $user->groups ?? [])) {
            $user->role()->associate(3);
            $user->saveQuietly(); // save() würde in nem endlos loop enden... hab ich gehört... >.>
        }
    }

    public function deleted(User $user)
    {
        //
    }
}
