<?php

namespace App\Observers;

use App\Models\Reminder;
use App\Models\SigTimeslot;

class SigTimeslotObserver
{
    public function updated(SigTimeslot $sigTimeslot): void {
        Reminder::updateSendTime($sigTimeslot);
    }

    public function deleted(SigTimeslot $sigTimeslot): void {
        $sigTimeslot->reminders()->delete();
    }
}
