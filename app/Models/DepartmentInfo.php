<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentInfo extends Model
{
    protected $guarded = [];

    public function sigEvent(): BelongsTo {
        return $this->belongsTo(SigEvent::class);
    }

    public function userRole(): BelongsTo {
        return $this->belongsTo(UserRole::class);
    }
}
