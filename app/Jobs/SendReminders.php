<?php

namespace App\Jobs;

use App\Models\Reminder;
use App\Models\SigFavorite;
use App\Models\SigTimeslot;
use App\Models\TimetableEntry;
use App\Notifications\Sig\SigFavoriteReminder;
use App\Notifications\Sig\SigTimeslotReminder;
use App\Notifications\TimetableEntry\TimetableEntryReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendReminders implements ShouldQueue
{
    use Queueable;

    public function handle(): void {
        $upcomingReminders = Reminder::where("send_at", "<=", now())->whereNull("sent_at")->limit(100)->get();
        foreach($upcomingReminders AS $reminder) {
            $remindable = null;

            if($reminder->remindable instanceof TimetableEntry)
                $remindable = new TimetableEntryReminder($reminder->remindable);
            if($reminder->remindable instanceof SigFavorite)
                $remindable = new SigFavoriteReminder($reminder->remindable->timetableEntry);
            if($reminder->remindable instanceof SigTimeslot)
                $remindable = new SigTimeslotReminder($reminder->remindable);

            if($remindable)
                $reminder->notifiable->notify($remindable);
        }

        // mass update
        Reminder::whereIn("id", $upcomingReminders->pluck("id"))->update([
            'sent_at' => time(),
        ]);
    }
}
