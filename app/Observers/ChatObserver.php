<?php

namespace App\Observers;

use App\Models\Chat;

class ChatObserver
{
    public function updated(Chat $chat) {
//        if($chat->isDirty("user_role_id")) {
//            $chat->userRole->users->each->notify(new ...);
//        }
    }
}
