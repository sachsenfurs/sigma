<?php

namespace App\Observers;

use App\Models\Post\PostChannelMessage;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;

class PostChannelMessageObserver
{
    public function created(PostChannelMessage $message): void {
        //
    }

    public function updated(PostChannelMessage $message): void {
        //
    }

    public function deleting(PostChannelMessage $message) {
        try {
            Telegram::deleteMessage([
                'chat_id' => $message->postChannel->channel_identifier,
                'message_id' => $message->message_id,
            ]);
        } catch(TelegramResponseException $e) {

        }
    }

    public function restored(PostChannelMessage $message): void {
        //
    }

    public function forceDeleted(PostChannelMessage $message): void {
        //
    }
}
