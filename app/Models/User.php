<?php

namespace App\Models;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\Dealer;
use App\Models\Post\Post;
use App\Models\Traits\HasNotificationRoutes;
use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements FilamentUser, HasAvatar, HasLocalePreference
{
    use HasFactory;
    use Notifiable;
    use HasNotificationRoutes;

    protected $guarded = [];

    protected $casts = [
        'groups' => 'array',
        'notification_channels' => 'array',
        'token_updated_at' => 'datetime',
    ];

    protected $hidden = [
        'email',
        'telegram_id',
        'telegram_user_id',
        'refresh_token',
        'token_updated_at',
    ];

    public function roles(): BelongsToMany {
        return $this->belongsToMany(
            UserRole::class,
            'user_user_roles'
        );
    }

    public function permissions(): ?Attribute {
        return Attribute::make(
            get: fn() => $this->roles->map->permissions->flatten()
        )->shouldCache();
    }

    public function hasPermission(Permission $checkPermission, PermissionLevel $level = PermissionLevel::READ): bool {
        /**
         * @var $userRolePermission UserRolePermission
         */
        if($this->permissions->filter(fn($p) => $p->permission == Permission::MANAGE_ADMIN AND $p->level == PermissionLevel::ADMIN)->count() > 0)
            return true;

        foreach($this->permissions AS $userRolePermission) {
            if($checkPermission == $userRolePermission->permission AND $userRolePermission->level->value >= $level->value)
                return true;
        }
        return false;
    }

    public function sigAttendees(): HasMany {
        return $this->hasMany(SigAttendee::class);
    }

    public function favorites(): HasMany {
        return $this->hasMany(SigFavorite::class);
    }


    public function reminders(): MorphMany {
        return $this->morphMany(Reminder::class, "notifiable");
    }

    public function sigHosts(): HasMany {
        return $this->hasMany(SigHost::class, "reg_id", "reg_id");
    }

    public function isSigHost(): bool {
        return $this->sigHosts()->count() > 0;
    }

    public function hasGroup(string $name): bool {
        return in_array($name, $this->groups);
    }

    public function sigTimeslots(): BelongsToMany {
        return $this->belongsToMany(SigTimeslot::class, "sig_attendees");
    }

    public function posts(): HasMany {
        return $this->hasMany(Post::class);
    }

    public function notifications(): MorphMany {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }

    /**
     * returns the telegram id used for all notification-related services
     */
    public function routeNotificationForTelegram(): ?string {
        return $this->telegram_user_id;
    }

    public function canAccessPanel(Panel $panel): bool {
        // needs at least 1 permission
        return $this->permissions->count() > 0;
    }

    public function getFilamentAvatarUrl(): ?string {
        return $this->avatar_thumb;
    }

    public function artists(): HasMany {
        return $this->hasMany(ArtshowArtist::class);
    }

    public function dealers(): HasMany {
        return $this->hasMany(Dealer::class);
    }

    public function artshowBids(): HasMany {
        return $this->hasMany(ArtshowBid::class);
    }

    public function chats(): HasMany {
        return $this->hasMany(Chat::class)
                    ->withAggregate("messages", "created_at", "max")
                    ->orderByRaw("CASE WHEN messages_max_created_at THEN messages_max_created_at ELSE created_at END DESC");
    }

    public function isAdmin(): bool {
        return $this->hasPermission(Permission::MANAGE_ADMIN, PermissionLevel::ADMIN);
    }

    public function sigFilledForms(): HasMany {
        return $this->hasMany(SigFilledForm::class);
    }

    public function preferredLocale() {
        return $this->language;
    }

    public function unreadMessagesCount(): Attribute {
        return Attribute::make(
            get: fn() => $this->chats()->withCount(["messages" => fn($query) => $query->where("user_id", "!=", $this->id)->whereNull("read_at")])->get()->sum("messages_count")
        )->shouldCache();
    }
}
