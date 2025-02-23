<?php

namespace App\Observers;

use App\Facades\NotificationService;
use App\Models\Reminder;
use App\Models\TimetableEntry;
use App\Notifications\TimetableEntry\CancelledFavoriteNotification;
use App\Notifications\TimetableEntry\CancelledNotification;
use App\Notifications\TimetableEntry\ChangedFavoriteNotification;
use App\Notifications\TimetableEntry\ChangedNotification;
use App\Notifications\TimetableEntry\NewNotification;

class TimetableEntryObserver
{

    public function created(TimetableEntry $timetableEntry): void {
        $this->createdUpdated($timetableEntry);
    }

    public function updated(TimetableEntry $timetableEntry): void {
        Reminder::updateSendTime($timetableEntry);

        $this->createdUpdated($timetableEntry);
    }

    public function createdUpdated(TimetableEntry $timetableEntry): void {
        if(!$timetableEntry->hide AND !$timetableEntry->sigEvent->is_private) {
            // event cancelled?
            if($timetableEntry->isDirty("cancelled") AND $timetableEntry->cancelled) {
                // global notification
                NotificationService::dispatchRoutedNotification(new CancelledNotification($timetableEntry));

                // favorite notification
                foreach($timetableEntry->favorites AS $fav)
                    $fav->user->notify(new CancelledFavoriteNotification($fav->timetableEntry));

                return;
            }

            // new event?
            if($timetableEntry->isDirty("new") AND $timetableEntry->new) {
                NotificationService::dispatchRoutedNotification(new NewNotification($timetableEntry));
                return;
            }

            // event changed?
            if($timetableEntry->updated_at != $timetableEntry->created_at) {
                // getDirty() contains the CHANGED data. But we need an array containing only the OLD data
                $oldData = collect($timetableEntry->getOriginal())->intersectByKeys($timetableEntry->getDirty());

                NotificationService::dispatchRoutedNotification(new ChangedNotification($timetableEntry, $oldData));

                // favs
                foreach($timetableEntry->favorites AS $fav)
                    $fav->user->notify(new ChangedFavoriteNotification($fav->timetableEntry, $oldData));

                return;
            }
        }
    }

    public function deleted(TimetableEntry $entry): void {
        $entry->reminders()->delete();
    }
}
