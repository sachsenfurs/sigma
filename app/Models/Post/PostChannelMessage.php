<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostChannelMessage extends Pivot
{
    protected $table = "post_channel_messages";
    protected $primaryKey = [
        "post_id",
        "post_channel_id"
    ];
    public $incrementing = false;

    public $timestamps = false;

    public function postChannel(): BelongsTo {
        return $this->belongsTo(PostChannel::class);
    }
    public function post(): BelongsTo {
        return $this->belongsTo(Post::class);
    }

    public function delete(): void {
        $this->postChannel->deleteMessage($this);
        parent::delete();
    }
}
