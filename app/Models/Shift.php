<?php

namespace App\Models;

use App\Enums\Necessity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    protected $guarded = [];

    protected $casts = [
        'necessity' => Necessity::class,
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function type(): BelongsTo {
        return $this->belongsTo(ShiftType::class);
    }

    public function sigLocation(): BelongsTo {
        return $this->belongsTo(SigLocation::class);
    }

}
