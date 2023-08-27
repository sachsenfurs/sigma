<?php

namespace App\Console\Commands;

use App\Models\SigReminder;
use App\Notifications\SigFavorite\SigFavoriteReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
    public function handle()
    {
        $upcomingReminders = SigReminder::where('executed_at', null)->where('send_at', '<=', time())->get();

        foreach ($upcomingReminders as $reminder) {
            $reminder->user->notify(new SigFavoriteReminder($reminder->timetableEntry));
            $reminder->executed_at = strtotime(Carbon::now());
            $reminder->save();
        }

        return 0;
    }
}
