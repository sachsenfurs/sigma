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
use Illuminate\Support\Facades\DB;

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
            if($timetableEntry->updated_at != $timetableEntry->created_at AND $timetableEntry->isDirty([
                    'start',
                    'sig_location_id'
                ])) {
                // getDirty() contains the CHANGED data. But we need an array containing only the OLD data
                $oldData = collect($timetableEntry->getOriginal())->intersectByKeys($timetableEntry->getDirty());

                NotificationService::dispatchRoutedNotification(new ChangedNotification($timetableEntry, $oldData));

                // favs
                foreach($timetableEntry->favorites AS $fav)
                    $fav->user->notify(new ChangedFavoriteNotification($fav->timetableEntry, $oldData));

                return;
            }
        }

        // dont' update event when approval state or "hide" changed
        if($timetableEntry->getOriginal("updated_at") == $timetableEntry->created_at) {
            if($timetableEntry->isDirty(['approval', 'hide', 'comment'])) {
                // revert changes without triggering any events:
                DB::statement("UPDATE timetable_entries SET updated_at = created_at WHERE id = " . $timetableEntry->id);
            }
        }
    }

    public function deleted(TimetableEntry $entry): void {
        $entry->reminders()->delete();
    }
}
