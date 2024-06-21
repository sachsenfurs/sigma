<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SigFormUserRole extends Model
{
    protected $guarded = [];

    public function sigForm(): BelongsTo {
        return $this->belongsTo(SigForm::class);
    }

    public function userRole(): BelongsTo {
        return $this->belongsTo(UserRole::class);
    }
}
