<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserShift extends Pivot
{
    protected $guarded = [];
    protected $table = 'shift_user';

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo {
        return $this->belongsTo(Shift::class);
    }
}
