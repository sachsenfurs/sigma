<?php

namespace App\Observers;

use App\Models\User;
use App\Models\TimetableEntry;
use App\Notifications\TimetableEntry\TimetableEntryTimeChanged;
use App\Notifications\TimetableEntry\TimetableEntryCancelled;
use App\Notifications\TimetableEntry\TimetableEntryLocationChanged;
use Notification;

class TimetableEntryObserver
{
    /**
     * Handle the TimetableEntry "updated" event.
     *
     * @param  \App\Models\TimetableEntry  $timetableEntry
     * @return void
     */
    public function updated(TimetableEntry $timetableEntry)
    {
        $users = User::where('telegram_user_id', '!=', null)->get();

        if (($timetableEntry->start != $timetableEntry->getOriginal('start')) || ($timetableEntry->end != $timetableEntry->getOriginal('end'))) {
            Notification::send($users, new TimetableEntryTimeChanged($timetableEntry));
        }

        if ($timetableEntry->cancelled != $timetableEntry->getOriginal('cancelled')) {
            Notification::send($users, new TimetableEntryCancelled($timetableEntry));
        }

        if ($timetableEntry->sig_location_id != $timetableEntry->getOriginal('sig_location_id')) {
            Notification::send($users, new TimetableEntryLocationChanged($timetableEntry));
        }
    }
}
