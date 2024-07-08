<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Parser\MarkdownParser;
use Telegram\Bot\Exceptions\TelegramResponseException;
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
        $text = $post->getTranslatedText($this->language);

        if($post->image) {
            $response = Telegram::sendPhoto([
                'chat_id' => $this->channel_identifier,
                'photo' => InputFile::create(Storage::disk("public")->path($post->image)),
                'caption' => $text,
                'parse_mode' => "MarkdownV2"
            ]);
        } else {
            $response = Telegram::sendMessage([
                'chat_id' => $this->channel_identifier,
                'text' => $text,
                'parse_mode' => "MarkdownV2"
            ]);
        }


        if(!$response->isError()) {
            $post->channels()->attach([
                [
                    'post_channel_id' => $this->id,
                    'message_id' => $response->getMessageId(),
                ]
            ]);
        }
    }
}
