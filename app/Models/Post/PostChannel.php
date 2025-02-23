<?php

namespace App\Models\Post;

use App\Models\Reminder;
use App\Models\Traits\HasNotificationRoutes;
use App\Observers\PostChannelObserver;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;


#[ObservedBy(PostChannelObserver::class)]
class PostChannel extends Model implements HasLocalePreference
{
    use Notifiable;
    use HasNotificationRoutes;

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

    public function routeNotificationForMail(): null {
        return null;
    }

    public function routeNotificationForTelegram(): ?string {
        return $this->channel_identifier;
    }

    public function preferredLocale() {
        return $this->language;
    }

    public function reminders(): MorphMany {
        return $this->morphMany(Reminder::class, "notifiable");
    }
}
