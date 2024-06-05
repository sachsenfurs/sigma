<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SigTimeslotReminder extends Model
{

    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function timeslot(): BelongsTo {
        return $this->belongsTo(SigTimeslot::class);
    }
}
