<?php

namespace App\Models;

use App\Models\DDAS\ArtshowArtist;
use App\Models\DDAS\ArtshowBid;
use App\Models\DDAS\Dealer;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
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


    public function role(): BelongsTo {
        return $this->belongsTo(UserRole::class, 'user_role_id');
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

    public function isSigHost(): HasMany|bool {
        if(SigHost::where('reg_id', $this->reg_id)->first()) {
            return true;
        } else {
            return false;
        }
    }

    public function hasGroup(string $name)
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
        // TODO: admin permission
        return true;
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

    public function unreadChats(): bool {
        return true;
    }

    public function unreadNotifications(): bool {
        return true;
    }
}
