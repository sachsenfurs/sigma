<?php

namespace App\Models;

use App\Enums\ChatStatus;
use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Resources\SigEventResource;
use App\Observers\ChatObserver;
use App\Providers\Filament\AdminPanelProvider;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Gate;

#[ObservedBy(ChatObserver::class)]
class Chat extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => ChatStatus::class,
    ];

    protected static function booted() {
//        if(!Gate::check("forceDeleteAny", Chat::class)) {
//            static::addGlobalScope('involved', function(Builder $query) {
//                $query->whereIn("user_role_id", auth()->user()?->roles?->pluck("id")?->toArray() ?? []);
//            });
//        }
    }

    public function scopeInvolved(Builder $query) {
        return $query->whereIn("user_role_id", auth()->user()?->roles?->pluck("id")?->toArray() ?? [])
            ->orWhere("user_id", auth()->id());
    }

    public function messages(): HasMany {
        return $this->hasMany(Message::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function userRole(): BelongsTo {
        return $this->belongsTo(UserRole::class);
    }

    public function subjectable(): MorphTo {
        return $this->morphTo();
    }

    public function unreadMessagesCount(): Attribute {
        return Attribute::make(
            get: fn() => $this->messages()->unread()->where("user_id", "!=", $this->user_id)->count()
        )->shouldCache();
    }

    public function markAsRead(): void {
        $user = auth()->user();
        $this->messages()->to($user)->unread()->update([
            'read_at' => now(),
        ]);

    }
}
