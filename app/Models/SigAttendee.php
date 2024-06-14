<?php

namespace App\Models;

use App\Observers\SigAttendeeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(SigAttendeeObserver::class)]
class SigAttendee extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sigTimeslot() {
        return $this->belongsTo(SigTimeslot::class);
    }

    public function timeslotReminders() {
        return auth()?->user()->timeslotReminders()->where("timeslot_id", $this->sigTimeslot->id);
    }
}
