<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Chat;
use App\Models\User;
use App\Settings\ChatSettings;

class ChatPolicy
{
    public function before(User $user): ?bool {
        if(!app(ChatSettings::class)->enabled)
            return false;

        return null;
    }

    public function view(User $user, Chat $chat): bool {
        return $user->hasPermission(Permission::MANAGE_CHATS, PermissionLevel::READ);
    }

    public function update(User $user, Chat $chat): bool {
        return $chat->user_id === $user->id OR $user->roles->contains($chat->user_role_id);
    }

//    public function viewAny(User $user): bool {
//        return $user->hasPermission(Permission::MANAGE_CHATS, PermissionLevel::READ);
//    }

    public function create() {
        return auth()->check();
    }
}
