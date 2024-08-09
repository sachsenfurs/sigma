<?php

namespace App\Models;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\Dealer;
use App\Models\Post\Post;
use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements FilamentUser, HasAvatar, HasLocalePreference
{
    use HasFactory;
    use Notifiable;

    protected $guarded = [
        'user_role_id',
    ];

    protected $casts = [
        'groups' => 'array',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = [
        'roles'
    ];

    public function roles(): BelongsToMany {
        return $this->belongsToMany(
            UserRole::class,
            'user_user_roles'
        );
    }

    public function permissions(): Collection {
        return $this->roles->map->permissions->flatten();
    }

    public function hasPermission(Permission $checkPermission, PermissionLevel $level = PermissionLevel::READ): bool {
        /**
         * @var $userRolePermission UserRolePermission
         */
        foreach($this->permissions() AS $userRolePermission) {
            if($checkPermission == $userRolePermission->permission AND $userRolePermission->level->value >= $level->value)
                return true;
        }
        return false;
    }



    public function notificationChannels(): HasMany {
        return $this->hasMany(UserNotificationChannel::class);
    }

    public function attendeeEvents(): HasMany {
        return $this->hasMany(SigAttendee::class);
    }

    public function favorites(): HasMany {
        return $this->hasMany(SigFavorite::class);
    }


    public function reminders(): HasMany {
        return $this->hasMany(SigReminder::class);
    }

    public function timeslotReminders(): HasMany {
        return $this->hasMany(SigTimeslotReminder::class);
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

    public function canAccessPanel(Panel $panel): bool {
        // needs at least 1 permission
        return $this->permissions()->count() > 0;
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

    public function chats() {
        return $this->hasMany(Chat::class);
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
}
