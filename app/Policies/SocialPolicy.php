<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Info\Social;
use App\Models\User;

class SocialPolicy
{

     /**
      * Overrides
      */

     public function before(User $user): ?bool {
         if($user->hasPermission(Permission::MANAGE_SETTINGS, PermissionLevel::ADMIN))
             return true;

         return null;
     }

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        return false;
    }

    public function deleteAny(): bool {
        return false;
    }

    public function view(User $user, Social $social): bool {
        return false;
    }

    public function create(User $user): bool {
        return false;
    }

    public function update(User $user, Social $social): bool {
        return false;
    }

    public function delete(User $user, Social $social): bool {
        return false;
    }

    public function restore(User $user, Social $social): bool {
        return false;
    }

    public function forceDelete(User $user, Social $social): bool {
        return false;
    }
}
