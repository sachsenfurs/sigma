<?php

namespace App\Policies;

use App\Enums\ChatStatus;
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
        if($chat->user_id == auth()->id())
            return true;

        return $user->hasPermission(Permission::MANAGE_CHATS, PermissionLevel::READ);
    }

    public function update(User $user, Chat $chat): bool {
        if($user->roles->contains($chat->user_role_id))
            return true;
        if($chat->status == ChatStatus::LOCKED)
            return false;

        return $chat->user_id === $user->id ;
    }

//    public function viewAny(User $user): bool {
//        return $user->hasPermission(Permission::MANAGE_CHATS, PermissionLevel::READ);
//    }

    public function create() {
        return auth()->check();
    }
}
