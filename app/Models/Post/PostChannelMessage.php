<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

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

    public function delete(): void {
        $this->postChannel->channelImplementation()->delete($this);
        parent::delete();
    }

    public function updateMessage() {
        $this->postChannel->channelImplementation()->update($this);
    }
}
