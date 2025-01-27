<?php

namespace App\Policies;

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
        return $chat->user_id === $user->id;
    }

    public function viewAny(): bool {
        return auth()->check();
    }

    public function create() {
        return auth()->check();
    }
}
