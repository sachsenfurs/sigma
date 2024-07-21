<?php

namespace App\Models;

use App\Observers\SigAttendeeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(SigAttendeeObserver::class)]
class SigAttendee extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function sigTimeslot(): BelongsTo {
        return $this->belongsTo(SigTimeslot::class);
    }

    public function timeslotReminders(): HasMany {
//        return $this->hasMany(SigTimeslotReminder::class)->where("user_id", auth()->user?->id);
        return auth()?->user()->timeslotReminders()->where("timeslot_id", $this->sigTimeslot->id);
    }
}
