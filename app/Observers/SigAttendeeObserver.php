<?php

namespace App\Observers;

use App\Models\SigAttendee;
use App\Models\SigTimeslot;
use App\Models\SigTimeslotReminder;
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
        $reminderAttributes = [
            'timeslot_id' => $sigAttendee->sig_timeslot_id,
            'minutes_before' => 15,
            'user_id' => $sigAttendee->user_id,
            'send_at' => strtotime(SigTimeslot::find(['id' => $sigAttendee->sig_timeslot_id])->first()->slot_start) - (15 * 60),
        ];

        $sigAttendee->user->timeslotReminders()->create($reminderAttributes);
   
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
        if($sigAttendee->sigTimeslot->reg_end < Carbon::now()) {
            return false;
        }
    }

    /**
     * Handle the SigAttendee "deleted" event.
     *
     * @param  \App\Models\SigAttendee  $sigAttendee
     * @return void
     */
    public function deleted(SigAttendee $sigAttendee) {
        if (SigTimeslotReminder::where('user_id', $sigAttendee->user_id)->where('timeslot_id', $sigAttendee->sig_timeslot_id)->exists()) {
            $reminder = SigTimeslotReminder::where('user_id', $sigAttendee->user_id)->where('timeslot_id', $sigAttendee->sig_timeslot_id)->first();
            $reminder->delete();
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
