<?php

namespace App\Models\Post;

use App\Models\User;
use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy(PostObserver::class)]
class Post extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function getTranslatedText($language="de"): string {
        return match($language) {
            'en' => $this->text_en,
            default => $this->text,
        };
    }

    public function messages(): BelongsToMany {
        return $this->belongsToMany(PostChannel::class, "post_channel_messages")
            ->using(PostChannelMessage::class)
            ->as('postChannelMessage')
            ->withPivot('message_id');
    }


    public function delete(): void {
        foreach($this->messages->pluck("postChannelMessage") AS $message) {
            $message->delete();
        }
        parent::delete();
    }
}
