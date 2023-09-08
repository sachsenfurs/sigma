<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostChannelMessage extends Pivot
{
    public $timestamps = false;

    public function postChannel() {
        return $this->belongsTo(PostChannel::class);
    }
    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function delete() {
        $this->postChannel->deleteMessage($this);
        parent::delete();
    }
}
