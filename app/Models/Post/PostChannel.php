<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
        if($messageId = $post->sendToChannel($this)) {
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
            $post->sendToChannel($this, true);
    }
}
