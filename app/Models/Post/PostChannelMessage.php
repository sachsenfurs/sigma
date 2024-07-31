<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Telegram\Bot\Exceptions\TelegramOtherException;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;

class PostChannelMessage extends Pivot
{
    protected $table = "post_channel_messages";

    public $timestamps = false;

    public function postChannel(): BelongsTo {
        return $this->belongsTo(PostChannel::class);
    }

    public function post(): BelongsTo {
        return $this->belongsTo(Post::class);
    }

    public function delete() {
        parent::delete();
        try {
            Telegram::deleteMessage([
                'chat_id' => $this->postChannel->channel_identifier,
                'message_id' => $this->message_id,
            ]);
        } catch(TelegramResponseException $e) {

        }
    }

    public function updateMessage() {
        try {
            if($this->post->image) {
    //            Telegram::editMessageMedia([
    //                'chat_id' => $this->post_channel_id,
    //                'message_id' => $this->message_id,
    //                'media' => InputMedia::make([
    //                    'type' => "photo",
    //                    'caption' => $this->post->getTranslatedText($this->postChannel->language),
    //                    'parse_mode' => "MarkdownV2",
    //                    'media' =>
    //                ])
    //            ]);
                Telegram::editMessageCaption([
                    'chat_id' => $this->postChannel->channel_identifier,
                    'message_id' => $this->message_id,
                    'caption' => $this->post->getTranslatedText($this->postChannel->language),
                    'parse_mode' => Post::$parseMode,
                ]);
            } else {
                Telegram::editMessageText([
                    'chat_id' => $this->postChannel->channel_identifier,
                    'message_id' => $this->message_id,
                    'parse_mode' => Post::$parseMode,
                    'text' => $this->post->getTranslatedText($this->postChannel->language),
                ]);
            }
        } catch(TelegramOtherException|TelegramResponseException $e) {
            // Message not found (if deleted manually or something else went wrong..)
        }
    }
}
