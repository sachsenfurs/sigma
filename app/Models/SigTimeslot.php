<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SigTimeslot extends Model
{
    protected $guarded = [];

    protected $casts = [
        'reg_start' => 'datetime',
        'reg_end' => 'datetime',
    ];

    public function timetableEntry(): BelongsTo {
        return $this->belongsTo(TimetableEntry::class);
    }

    public function sigAttendees(): HasMany {
        return $this->hasMany(SigAttendee::class);
    }

    public function getAttendeeNames(): array {
        $attendees = [];
        foreach ($this->sigAttendees as $attendee) {
            $user = [
                'name' => $attendee->user->name,
                'id' => $attendee->user->id,
            ];
            array_push($attendees, $user);
        }

        return $attendees;
    }
}
