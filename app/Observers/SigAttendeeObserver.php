<?php

namespace App\Observers;

use App\Models\SigAttendee;

class SigAttendeeObserver
{
    public function deleted(SigAttendee $sigAttendee) {
        $sigAttendee->reminders()->delete();
    }

}
