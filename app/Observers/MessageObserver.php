<?php

namespace App\Observers;

use App\Models\Message;
use App\Notifications\Chat\NewChatMessage;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void {
        // determine the last one who messaged the user
        $lastUser = $message->chat->messages()->whereNot('user_id', $message->user_id)->latest()->first()?->user;

        if($lastUser)
            $toBeNotifiedUsers = [ $lastUser ];
        else // no one found? notify the whole department!
            $toBeNotifiedUsers = $message->chat->userRole->users;

        foreach ($toBeNotifiedUsers as $user) {
            $user->notify((new NewChatMessage($message)));
        }
    }

}
