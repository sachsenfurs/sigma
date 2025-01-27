<?php

namespace App\Observers;

use App\Models\Chat;
use App\Models\UserRole;
use App\Notifications\Chat\NewChatMessage;

class ChatObserver
{
    /**
     * Handle the Chat "created" event.
     */
    public function created(Chat $chat): void {

    }
}
