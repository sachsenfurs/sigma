<?php

namespace App\Models;

use App\Models\DDAS\ArtshowArtist;
use App\Models\DDAS\ArtshowBid;
use App\Models\DDAS\Dealer;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable implements FilamentUser, HasAvatar
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


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            UserRole::class,
            'user_user_roles'
        );
    }

    public function permissions(): Collection
    {
        return $this->roles->map->permissions->flatten()->pluck('name')->unique();
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
        return $this->hasMany(SigHost::class, "reg_id");
    }

    public function isSigHost(): bool {
        return $this->sigHosts()->count() > 0;
    }

    public function hasGroup(string $name): bool
    {
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
}
