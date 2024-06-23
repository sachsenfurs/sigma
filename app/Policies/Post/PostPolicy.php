<?php

namespace App\Policies\Post;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Post\Post;
use App\Models\User;

class PostPolicy
{

     /**
      * Overrides
      */

    public function before(User $user): ?bool {
        if($user->hasPermission(Permission::MANAGE_POSTS, PermissionLevel::ADMIN))
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

    public function view(User $user, Post $post): bool {
        if($user->hasPermission(Permission::MANAGE_POSTS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function create(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_POSTS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function update(User $user, Post $post): bool {
        if($user->hasPermission(Permission::MANAGE_POSTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function delete(User $user, Post $post): bool {
        if($user->hasPermission(Permission::MANAGE_POSTS, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function restore(User $user, Post $post): bool {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool {
        return false;
    }
}
