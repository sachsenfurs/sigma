<?php

namespace App\Observers;

use App\Models\Message;
use App\Notifications\NewChatMessage;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        $toBeNotifiedUsers = $message->chat()->messages()->where('user_id', '!', $message->user_id)->all();
        $department = $message->chat->department;
        $sender = $message->user;
        foreach ($toBeNotifiedUsers as $user) {
            $user->notify(new NewChatMessage($department, $sender, $message->chat, $message->text));
        }

    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "restored" event.
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}
