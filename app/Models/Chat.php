<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $guarded = [];

    public function messages(): HasMany {
        return $this->hasMany(Message::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function userRole(): BelongsTo {
        return $this->belongsTo(UserRole::class);
    }

    public function unreadMessagesCount(): Attribute {
        return Attribute::make(
            get: fn() => $this->messages->filter(fn($m) => $m->user_id != $this->user_id AND !$m->read_at)->count()
        );
    }

    public function markAsRead() {
        $this->messages()->unread()->update([
            'read_at' => now(),
        ]);
    }
}
