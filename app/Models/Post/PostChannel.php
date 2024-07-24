<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;

class PostChannel extends Model
{
    public $timestamps = false;
    protected $table = "post_channels";

    protected $guarded = [];

    public function posts(): HasManyThrough {
        return $this->hasManyThrough(
            Post::class,
            PostChannelMessage::class,
            "post_channel_id",
            "id",
            "id",
            "post_id"
        );
    }

    public function sendMessage(Post $post): void {
        if($messageId = $this->sendPostToChannel($post, $this->channel_identifier)) {
            $post->channels()->attach([
                [
                    'post_channel_id' => $this->id,
                    'message_id' => $messageId,
                ]
            ]);
        }
    }

    public function sendTestMessage(Post $post): void {
        if($this->test_channel_identifier)
            $this->sendPostToChannel($post,$this->test_channel_identifier);
    }

    public function sendPostToChannel(Post $post, int $channel_identifier): int|false {
        $text = $post->getTranslatedText($this->language);

        if($post->image) {
            $response = Telegram::sendPhoto([
                'chat_id' => $channel_identifier,
                'photo' => InputFile::create(Storage::disk("public")->path($post->image)),
                'caption' => $text,
                'parse_mode' => "markdown"
            ]);
        } else {
            $response = Telegram::sendMessage([
                'chat_id' => $channel_identifier,
                'text' => $text,
                'parse_mode' => "markdown"
            ]);
        }

        return $response->isError() ? false : $response->getMessageId();
    }
}
