<?php

namespace App\Observers;

use App\Models\SigAttendee;
use Carbon\Carbon;

class SigAttendeeObserver
{
    /**
     * Handle the SigAttendee "created" event.
     *
     * @param  \App\Models\SigAttendee  $sigAttendee
     * @return void
     */
    public function created(SigAttendee $sigAttendee)
    {
        //
    }

    /**
     * Handle the SigAttendee "updated" event.
     *
     * @param  \App\Models\SigAttendee  $sigAttendee
     * @return void
     */
    public function updated(SigAttendee $sigAttendee)
    {
        //
    }

    /**
     * Handle the SigAttendee "deleting" event.
     *
     * @param  \App\Models\SigAttendee  $sigAttendee
     * @return void
     */
    public function deleting(SigAttendee $sigAttendee)
    {
        if($sigAttendee->sigTimeslot()->reg_end < Carbon::now()) {
            return false;
        }
    }

    /**
     * Handle the SigAttendee "restored" event.
     *
     * @param  \App\Models\SigAttendee  $sigAttendee
     * @return void
     */
    public function restored(SigAttendee $sigAttendee)
    {
        //
    }

    /**
     * Handle the SigAttendee "force deleted" event.
     *
     * @param  \App\Models\SigAttendee  $sigAttendee
     * @return void
     */
    public function forceDeleted(SigAttendee $sigAttendee)
    {
        //
    }
}
