<?php

namespace App\Observers;

use App\Models\SigTimeslot;
use App\Models\SigTimeslotReminder;
use Carbon\Carbon;

class SigTimeslotReminderObserver
{
    /**
     * Handle the SigTimeslotReminder "creating" event.
     *
     * @param  \App\Models\SigTimeslotReminder  $sigTimeslotReminder
     * @return void
     */
    public function creating(SigTimeslotReminder $sigTimeslotReminder)
    {
        if ($sigTimeslotReminder->send_at < Carbon::now()->timestamp) {
            return false;
        }
        
        if (SigTimeslotReminder::where('user_id', $sigTimeslotReminder->user_id)->where('timeslot_id', $sigTimeslotReminder->timeslot_id)->exists()) {
            return false;
        }

    }

    /**
     * Handle the SigTimeslotReminder "created" event.
     *
     * @param  \App\Models\SigTimeslotReminder  $sigTimeslotReminder
     * @return void
     */
    public function created(SigTimeslotReminder $sigTimeslotReminder) {
        
    }

    /**
     * Handle the SigTimeslotReminder "updated" event.
     *
     * @param  \App\Models\SigTimeslotReminder  $sigTimeslotReminder
     * @return void
     */
    public function updated(SigTimeslotReminder $sigTimeslotReminder)
    {
        //
    }

    /**
     * Handle the SigTimeslotReminder "deleted" event.
     *
     * @param  \App\Models\SigTimeslotReminder  $sigTimeslotReminder
     * @return void
     */
    public function deleted(SigTimeslotReminder $sigTimeslotReminder)
    {
        //
    }

    /**
     * Handle the SigTimeslotReminder "restored" event.
     *
     * @param  \App\Models\SigTimeslotReminder  $sigTimeslotReminder
     * @return void
     */
    public function restored(SigTimeslotReminder $sigTimeslotReminder)
    {
        //
    }

    /**
     * Handle the SigTimeslotReminder "force deleted" event.
     *
     * @param  \App\Models\SigTimeslotReminder  $sigTimeslotReminder
     * @return void
     */
    public function forceDeleted(SigTimeslotReminder $sigTimeslotReminder)
    {
        //
    }
}
