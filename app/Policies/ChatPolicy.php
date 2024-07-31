<?php

namespace App\Policies;

use App\Enums\Approval;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    public function view(User $user, Chat $chat): bool {
        return $chat->user_id === $user->id;
    }
}
