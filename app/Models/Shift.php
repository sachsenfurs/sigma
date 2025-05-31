<?php

namespace App\Models;

use App\Enums\Necessity;
use App\Models\Traits\HasReminders;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasReminders;

    protected $guarded = [];

    protected $casts = [
        'necessity' => Necessity::class,
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    protected static function booted() {
        self::addGlobalScope('ownDepartment', function(Builder $query) {
            $query->whereHas('type', function (Builder $query) {
                if($user = auth()->user()) {
                    return $query->whereIn("user_role_id", $user->roles->pluck("id"));
                }
                return $query;
            });
        });
    }

    public function type(): BelongsTo {
        return $this->belongsTo(ShiftType::class, "shift_type_id");
    }

    public function sigLocation(): BelongsTo {
        return $this->belongsTo(SigLocation::class);
    }

    public function userShifts(): HasMany {
        return $this->hasMany(UserShift::class);
    }

//    public function users(): HasManyThrough {
//        return $this->hasManyThrough(User::class, UserShift::class, "user_id", "id");
//    }

//    public function users(): BelongsToMany {
//        return $this->belongsToMany(User::class);
//    }
//
    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, "shift_user")
            ->using(UserShift::class)
            ->withTimestamps();
    }

}
