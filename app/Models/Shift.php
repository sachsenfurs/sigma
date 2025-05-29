<?php

namespace App\Models;

use App\Enums\Necessity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $guarded = [];

    protected $casts = [
        'necessity' => Necessity::class,
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function type(): BelongsTo {
        return $this->belongsTo(ShiftType::class, "shift_type_id");
    }

    public function sigLocation(): BelongsTo {
        return $this->belongsTo(SigLocation::class);
    }

    public function userShifts(): HasMany {
        return $this->hasMany(UserShift::class);
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, "shift_user")
            ->using(UserShift::class)
            ->withTimestamps();
    }

}
