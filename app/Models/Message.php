<?php

namespace App\Models;

use App\Observers\MessageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[ObservedBy(MessageObserver::class)]
class Message extends Model
{
    protected $guarded = [];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function chat(): BelongsTo {
        return $this->belongsTo(Chat::class);
    }

    public function time(): Attribute {
        return Attribute::make(
            get: fn() => $this->created_at->format("d.m.Y, H:i")
        );
    }

    public function avatar() : Attribute {
        return Attribute::make(
            get: fn() => ($this->user?->avatar ?? "")
        );
    }

    public function scopeTo(Builder $query, User $user = null) {
        if($user == null)
            $user = auth()->user();
        $query->where("user_id","!=", $user->id)
              ->whereHas("chat", function(Builder $query) use ($user) {
            $query->where("user_id", $user?->id);
        });
    }

    public function scopeUnread(Builder $query) {
        $query->whereNull("read_at");
    }
}
