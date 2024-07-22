<?php

namespace App\Models\Post;

use App\Models\User;
use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

#[ObservedBy(PostObserver::class)]
class Post extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function getTranslatedText($language="de"): string {
        return match($language) {
            'en' => $this->text_en ?? $this->text ?? "",
            default => $this->text ?? "",
        };
    }

    public function textLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? $this->text_en : $this->text
        );
    }

    public function channels(): BelongsToMany {
        return $this->belongsToMany(PostChannel::class, "post_channel_messages")
            ->using(PostChannelMessage::class)
            ->as('postChannelMessage')
            ->withPivot('message_id');
    }

    public function messages(): HasMany {
        return $this->hasMany(PostChannelMessage::class);
    }
}
