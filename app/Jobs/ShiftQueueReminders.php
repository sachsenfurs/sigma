<?php

namespace App\Jobs;

use App\Models\Shift;
use App\Models\UserShift;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ShiftQueueReminders implements ShouldQueue
{
    use Queueable;

    // determine all upcoming shifts and send out reminder
    public function handle(): void {
        $upcoming = Shift::where("start", ">", now())
                         ->where("start", "<", now()->addMinutes(15))
                         ->with(["reminders", "userShifts.shift.type"])
                         ->get();
        foreach($upcoming AS $shift) {
             /**
             * @var Shift $shift
             */
            if($shift->reminders->count() == 0) {
                if($shift->team) {
                    // notify the whole department
                    $shift->reminders()->make()->notifiable()->associate($shift->type->userRole)->save();
                } else {
                    $shift->userShifts->each(function(UserShift $userShift) use ($shift) {
                        $shift->reminders()->make()->notifiable()->associate($userShift->user)->save();
                    });
                }
            }
        }
    }
}
