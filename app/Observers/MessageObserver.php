<?php

namespace App\Observers;

use App\Enums\ChatStatus;
use App\Models\Message;
use App\Notifications\Chat\NewChatMessageNotification;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void {
        if($message->chat->user_id == $message->user_id) {
            // message direction: user => admin
            // determine the last one who messaged the user
            $lastUser = $message->chat->messages()->whereNot('user_id', $message->user_id)->latest()->first()?->user;
        } else {
            // message direction: admin => user
            $lastUser = $message->chat->user;
        }

        if($lastUser)
            $toBeNotifiedUsers = [ $lastUser ];
        else // no one found? notify the whole department!
            $toBeNotifiedUsers = $message->chat->userRole->users;

        foreach ($toBeNotifiedUsers as $user) {
            $user->notify((new NewChatMessageNotification($message)));
        }

        // re-open chat if chat owner responds again
        if($message->chat->user_id == $message->user_id) {
            $message->chat->status = ChatStatus::OPEN;
            $message->chat->save();
        }
    }

}
