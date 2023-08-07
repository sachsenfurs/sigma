<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

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
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    /**
     * Define the relationship between users and their attended events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendeeEvents()
    {
        return $this->hasMany(SigAttendee::class);
    }

    /**
     * Define the relationship between users and their favorites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
        return $this->hasMany(SigFavorite::class);
    }

    /**
     * Define the relationship between users and their favorites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function isSigHost()
    {
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
}
