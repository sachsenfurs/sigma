<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShiftType extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    protected static function booted() {
        self::addGlobalScope('ownDepartment', function(Builder $query) {
            if($user = auth()->user())
                $query->whereIn("user_role_id", $user->roles->pluck("id"));
            return $query;
        });
    }

    public function shifts(): HasMany {
        return $this->hasMany(Shift::class);
    }

    public function userRole(): BelongsTo {
        return $this->belongsTo(UserRole::class);
    }
}
