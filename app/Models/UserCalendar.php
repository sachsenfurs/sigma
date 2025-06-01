<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCalendar extends Model
{
    use HasUuids;

    protected $casts = [
        'settings' => "json",
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
