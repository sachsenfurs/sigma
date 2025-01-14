<?php

namespace App\Console\Commands;

use App\Models\SigFavorite;
use App\Models\SigReminder;
use App\Models\SigTimeslotReminder;
use App\Notifications\SigTimeslot\SigTimeslotReminder as SigTimeSlotReminderNotification;
use App\Notifications\SigFavorite\SigFavoriteReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use \Console;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out all current reminders via Telegram';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $upcomingReminders = SigReminder::where('executed_at', null)->where('send_at', '<=', time())->get();

        foreach ($upcomingReminders as $reminder) {
            if ($reminder->user->telegram_user_id) {
                try {
                    app()->setLocale($reminder->user->language);
                    $reminder->user->notify(new SigFavoriteReminder($reminder->timetableEntry, $reminder));
                    $reminder->executed_at = strtotime(Carbon::now());
                    $reminder->save();
                } catch (\Exception $e) {
                    $this->info($e->getMessage());
                }
            } else {
                $reminder->delete();
            }
        }

        $upcomingTimeslotReminders = SigTimeslotReminder::where('executed_at', null)->where('send_at', '<=', time())->get();

        foreach ($upcomingTimeslotReminders as $reminder) {
            if ($reminder->user->telegram_user_id && $reminder->send_at <= strtotime('-1 hour')) {
                try {
                    app()->setLocale($reminder->user->language);
                    $reminder->user->notify(new SigTimeslotReminderNotification($reminder->timeslot, $reminder));
                    $reminder->executed_at = strtotime(Carbon::now());
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
