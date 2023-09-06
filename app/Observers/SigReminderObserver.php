<?php

namespace App\Observers;

use App\Models\SigReminder;
use Carbon\Carbon;

class SigReminderObserver
{
    /**
     * Handle the SigReminder "created" event.
     *
     * @param  \App\Models\SigReminder  $sigReminder
     * @return void
     */
    public function created(SigReminder $sigReminder)
    {
        if ($sigReminder->send_at < Carbon::now()) {
            return false;
        }
        
        if (SigReminder::where('user_id', auth()->user()->id)->where('timetable_entry_id', $sigReminder->timetable_entry_id)->exists()) {
            return false;
        }

    }

    /**
     * Handle the SigReminder "updated" event.
     *
     * @param  \App\Models\SigReminder  $sigReminder
     * @return void
     */
    public function updated(SigReminder $sigReminder)
    {
        //
    }

    /**
     * Handle the SigReminder "deleted" event.
     *
     * @param  \App\Models\SigReminder  $sigReminder
     * @return void
     */
    public function deleted(SigReminder $sigReminder)
    {
        //
    }

    /**
     * Handle the SigReminder "restored" event.
     *
     * @param  \App\Models\SigReminder  $sigReminder
     * @return void
     */
    public function restored(SigReminder $sigReminder)
    {
        //
    }

    /**
     * Handle the SigReminder "force deleted" event.
     *
     * @param  \App\Models\SigReminder  $sigReminder
     * @return void
     */
    public function forceDeleted(SigReminder $sigReminder)
    {
        //
    }
}
