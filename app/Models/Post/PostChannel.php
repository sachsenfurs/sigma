<?php

namespace App\Models\Post;

use App\Models\Reminder;
use App\Models\Traits\HasNotificationRoutes;
use App\Observers\PostChannelObserver;
use App\Services\PostChannels\PostChannelImplementation;
use App\Services\PostChannels\PostChannelManager;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function implementation(): Attribute {
        return Attribute::make(
            get: fn(?string $value) => PostChannelManager::normalize($value),
            set: fn(?string $value) => PostChannelManager::normalize($value),
        );
    }

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
        $this->channelImplementation()->send($this, $post);
    }

    public function sendTestMessage(Post $post): void {
        $this->channelImplementation()->sendTest($this, $post);
    }

    public function routeNotificationForMail(): null {
        return null;
    }

    public function routeNotificationForTelegram(): ?string {
        return $this->implementation === PostChannelManager::TELEGRAM ? $this->channel_identifier : null;
    }

    public function preferredLocale() {
        return $this->language;
    }

    public function reminders(): MorphMany {
        return $this->morphMany(Reminder::class, "notifiable");
    }

    public function channelImplementation(): PostChannelImplementation {
        return app(PostChannelManager::class)->resolve($this->implementation);
    }
}
