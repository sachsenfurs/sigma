<?php

namespace App\Console\Commands;

use App\Models\SigReminder;
use App\Models\SigTimeslotReminder;
use App\Notifications\Sig\SigFavoriteReminder;
use App\Notifications\Sig\SigTimeslotReminder as SigTimeSlotReminderNotification;
use Illuminate\Console\Command;

class SendReminders extends Command
{

    protected $signature = 'reminders:send';

    protected $description = 'Send out all current reminders via Telegram';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $upcomingReminders = SigReminder::where('executed_at', null)->where('send_at', '<=', time())->get();

        foreach ($upcomingReminders as $reminder) {
                try {
                    $reminder->user->notify((new SigFavoriteReminder($reminder->timetableEntry, $reminder))->locale($reminder->user->language));
                    $reminder->executed_at = now();
                    $reminder->save();
                } catch (\Exception $e) {
                    $this->info($e->getMessage());
                }
        }

        $upcomingTimeslotReminders = SigTimeslotReminder::where('executed_at', null)->where('send_at', '<=', time())->get();

        foreach ($upcomingTimeslotReminders as $reminder) {
            if ($reminder->send_at <= strtotime('-1 hour')) {
                try {
                    $reminder->user->notify((new SigTimeslotReminderNotification($reminder->timeslot, $reminder))->locale($reminder->user->language));
                    $reminder->executed_at = now();
                    $reminder->save();
                    $this->info('Reminder with id: '. $reminder->id . ' sent');
                } catch (\Exception $e) {
                    $this->info($e->getMessage());
                }
            } else {
                $reminder->delete();
                $this->info('Reminder with id: '. $reminder->id . ' skipped');
            }
        }

        return 0;
    }
}
