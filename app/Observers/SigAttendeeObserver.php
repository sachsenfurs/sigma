<?php

namespace App\Observers;

use App\Models\Reminder;
use App\Models\SigAttendee;

class SigAttendeeObserver
{

    public function created(SigAttendee $sigAttendee): void {
        if($sigAttendee->sigTimeslot->slot_start->isFuture()) {
            $reminder = new Reminder();
            $reminder->remindable()->associate($sigAttendee->sigTimeslot);
            $reminder->notifiable()->associate($sigAttendee->user);
            $reminder->save();
        }
    }

    public function deleted(SigAttendee $sigAttendee): void {
        $sigAttendee->user->reminders()->whereMorphedTo('remindable', $sigAttendee->sigTimeslot)->delete();
    }

}
