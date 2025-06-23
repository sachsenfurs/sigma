<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCalendar extends Model
{
    use HasUuids;

    protected $casts = [
        'settings' => "collection",
    ];

    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function name(): Attribute {
        return Attribute::make(
            get: fn() => substr($this->id, -6)
        );
    }
}
