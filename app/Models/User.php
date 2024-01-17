<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Define the relationship between users and their role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    /**
     * Define the relationship between users and their attended events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendeeEvents(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(SigAttendee::class);
    }

    /**
     * Define the relationship between users and their favorites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(SigFavorite::class);
    }

    /**
     * Define the relationship between users and their reminders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reminders(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(SigReminder::class);
    }

    /**
     * Define the relationship between users and their timeslot-reminders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeslotReminders(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(SigTimeslotReminder::class);
    }

    /**
     * Define the relationship between users and their favorites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function isSigHost(): \Illuminate\Database\Eloquent\Relations\HasMany|bool {
        if(SigHost::where('reg_id', $this->reg_id)->first()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Define the relationship between users and their favorites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasGroup(string $name)
    {
        return in_array($name, $this->groups);
    }

    public function sigTimeslots(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
        return $this->belongsToMany(SigTimeslot::class, "sig_attendees");
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Post::class);
    }

    public function canAccessPanel(Panel $panel): bool {
        // TODO: admin permission
        return true;
    }
}
