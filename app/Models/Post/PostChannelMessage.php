<?php

namespace App\Models\Post;

use App\Observers\PostChannelMessageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\InputMedia\InputMedia;

#[ObservedBy(PostChannelMessageObserver::class)]
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

    public function updateMessage() {
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
            ]);
        } else {
            Telegram::editMessageText([
                'chat_id' => $this->postChannel->channel_identifier,
                'message_id' => $this->message_id,
                'parse_mode' => "MarkdownV2",
                'text' => $this->post->getTranslatedText($this->postChannel->language),
            ]);
        }
    }
}
