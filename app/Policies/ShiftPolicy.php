<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Sig\ManageEventPolicy;

class ShiftPolicy extends ManageEventPolicy
{

    public function update(User $user): bool {
        if($this->before($user) === false)
            return false;

        return true;
    }
}
