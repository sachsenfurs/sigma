<?php

namespace App\Jobs;

use App\Models\Reminder;
use App\Models\SigFavorite;
use App\Models\SigReminder;
use App\Models\SigTimeslotReminder;
use App\Models\TimetableEntry;
use App\Notifications\Sig\SigFavoriteReminder;
use App\Notifications\Sig\SigTimeslotReminder as SigTimeSlotReminderNotification;
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
                $remindable = null; // ... new TimetableEntryReminder
            if($reminder->remindable instanceof SigFavorite)
                $remindable = new SigFavoriteReminder();
            if($reminder->remindable instanceof SigTimeslotReminder)
                $remindable = new SigTimeslotReminder();

            if($remindable)
                $reminder->notifiable->notify($remindable);
        }

        // mass update
        Reminder::whereIn("id", $upcomingReminders->pluck("id"))->update([
            'sent_at' => now(),
        ]);
    }
}
