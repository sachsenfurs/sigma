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
        $text = $post->getTranslatedText($this->language);

        if($post->image) {
            $response = Telegram::sendPhoto([
                'chat_id' => $this->channel_identifier,
                'photo' => InputFile::create(Storage::path("") . $post->image),
                'caption' => $text,
                'parse_mode' => "HTML"
            ]);
        } else {
            $response = Telegram::sendMessage([
                'chat_id' => $this->channel_identifier,
                'text' => $text,
                'parse_mode' => "HTML"
            ]);
        }


        if(!$response->isError()) {
            $post->messages()->attach([
                1 => [
                    'post_id' => $post->id,
                    'post_channel_id' => $this->id,
                    'message_id' => $response->getMessageId(),
                ]
            ]);
        }
    }

    public function deleteMessage(PostChannelMessage $message): void {
        Telegram::deleteMessage([
            'chat_id' => $this->channel_identifier,
            'message_id' => $message->message_id,
        ]);
    }
}
