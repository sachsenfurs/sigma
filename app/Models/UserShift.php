<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserShift extends Pivot
{
    protected $guarded = [];
    protected $table = 'shift_user';

    protected static function booted() {
        self::addGlobalScope('ownDepartment', function(Builder $query) {
            $query->whereHas('shift.type', function (Builder $query) {
                if($user = auth()->user()) {
                    return $query->whereIn("user_role_id", $user->roles->pluck("id"));
                }
                return $query;
            });
        });
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo {
        return $this->belongsTo(Shift::class);
    }
}
