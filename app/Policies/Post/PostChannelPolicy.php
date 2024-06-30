<?php

namespace App\Policies\Post;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Post\PostChannel;
use App\Models\User;

class PostChannelPolicy
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
        if($user->hasPermission(Permission::MANAGE_POSTS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function view(User $user, PostChannel $postChannel): bool {
        if($user->hasPermission(Permission::MANAGE_POSTS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function create(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_SETTINGS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function update(User $user, PostChannel $postChannel): bool {
        if($user->hasPermission(Permission::MANAGE_SETTINGS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function delete(User $user, PostChannel $postChannel): bool {
        if($user->hasPermission(Permission::MANAGE_SETTINGS, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function restore(User $user, PostChannel $postChannel): bool {
        return false;
    }

    public function forceDelete(User $user, PostChannel $postChannel): bool {
        return false;
    }
}
