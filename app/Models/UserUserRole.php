<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserUserRole extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo {
        return $this->belongsTo(UserRole::class);
    }
}
