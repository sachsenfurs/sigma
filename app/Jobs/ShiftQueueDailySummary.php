<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserShift;
use App\Notifications\Shift\ShiftSummaryReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Queue\Queueable;

class ShiftQueueDailySummary implements ShouldQueue
{
    use Queueable;

    public function handle(): void {
        $userWithShifts = User::whereHas("userShifts.shift", function(Builder $query) {
               return $query->where("start", ">", now()->subMinutes(30))
                   ->where("start", "<", now()->addDay()->addMinutes(30));
            })
            ->with(["userShifts.shift.type"])
            ->get();

        foreach($userWithShifts AS $user) {
            $user->notify(
                new ShiftSummaryReminder(
                    $user->userShifts->filter(function(UserShift $userShift) {
                        return $userShift->shift->start->isToday();
                    })
                )
            );
        }

    }
}
