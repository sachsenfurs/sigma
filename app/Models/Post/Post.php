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
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;

#[ObservedBy(PostObserver::class)]
class Post extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public static $parseMode = "markdown";

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

    public function getSafeHTML() {
        $rawText = preg_replace(
            "#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is",
            "\\1<a href=\"\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>",
            e($this->text_localized)
        );

        return nl2br($rawText, false);
    }

    public function sendToChannel(PostChannel $channel, bool $test=false): int|false {
        $text = $this->getTranslatedText($channel->language);

        $target_chat_id = $test ? $channel->test_channel_identifier : $channel->channel_identifier;

        if($this->image) {
            $response = Telegram::sendPhoto([
                'chat_id' => $target_chat_id,
                'photo' => InputFile::create(Storage::disk("public")->path($this->image)),
                'caption' => $text,
                'parse_mode' => self::$parseMode
            ]);
        } else {
            $response = Telegram::sendMessage([
                'chat_id' => $target_chat_id,
                'text' => $text,
                'parse_mode' => self::$parseMode
            ]);
        }

        return $response->isError() ? false : $response->getMessageId();
    }
}
