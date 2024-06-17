<?php

namespace App\Observers;

use App\Models\Chat;

class ChatObserver
{
    /**
     * Handle the Chat "created" event.
     */
    public function created(Chat $chat): void
    {
        /*
         * 
         * Activate this once we know if we want to send a message out after a new chat was created.
         * 
         
        $attribudes = [
            'user_id' => '1'
        ];

        if ($chat->user()->language == 'en') {
            $attribudes['text'] = 'Hi ' . $chat->user()->nickname . ',\n our team will answer as fast as possible once you\'ve reached out to us.';
        } elseif ($chat->user()->language == 'de') {
            $attribudes['text'] = 'Hi ' . $chat->user()->nickname . ',\n user Team wird dir so schnell wie möglich Antworten, sobald du uns eine Nachricht geschrieben hast.';
        } else {
            $attribudes['text'] = 'Hi ' . $chat->user()->nickname . ',\n our team will answer as fast as possible once you\'ve reached out to us.';
        }
        $chat->messages()->create($attribudes);
        */
    }

    /**
     * Handle the Chat "updated" event.
     */
    public function updated(Chat $chat): void
    {
        //
    }

    /**
     * Handle the Chat "deleted" event.
     */
    public function deleted(Chat $chat): void
    {
        //
    }

    /**
     * Handle the Chat "restored" event.
     */
    public function restored(Chat $chat): void
    {
        //
    }

    /**
     * Handle the Chat "force deleted" event.
     */
    public function forceDeleted(Chat $chat): void
    {
        //
    }
}
