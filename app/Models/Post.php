<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getTranslatedText($language="de") {
        return match($language) {
            'en' => $this->text_en,
            'de' => $this->text_de,
        };
    }

    public function messages() {
        return $this->belongsToMany(PostChannel::class, "post_channel_messages")
            ->using(PostChannelMessage::class)
            ->as('postChannelMessage')
            ->withPivot('message_id');
    }



    public function delete() {
        foreach($this->messages->pluck("postChannelMessage") AS $message) {
            $message->delete();
        }
        parent::delete();
    }
}
