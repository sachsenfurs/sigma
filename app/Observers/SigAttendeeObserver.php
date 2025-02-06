<?php

namespace App\Observers;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\SigAttendee;
use App\Models\SigTimeslot;
use App\Models\SigTimeslotReminder;
use App\Models\User;
use Carbon\Carbon;

class SigAttendeeObserver
{

    public function created(SigAttendee $sigAttendee) {
        $reminderAttributes = [
            'timeslot_id' => $sigAttendee->sig_timeslot_id,
            'minutes_before' => 15,
            'user_id' => $sigAttendee->user_id,
            'send_at' => strtotime(SigTimeslot::find(['id' => $sigAttendee->sig_timeslot_id])->first()->slot_start) - (15 * 60),
        ];

        $sigAttendee->user->timeslotReminders()->create($reminderAttributes);

    }

    public function updated(SigAttendee $sigAttendee) {
        //
    }

    public function deleting(SigAttendee $sigAttendee) {
        if($sigAttendee->sigTimeslot->reg_end < Carbon::now() AND !auth()->user()->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE)) {
            return false;
            // TODO: refactoring. move auth checks to policy
        }
    }


    public function deleted(SigAttendee $sigAttendee) {
        if (SigTimeslotReminder::where('user_id', $sigAttendee->user_id)->where('timeslot_id', $sigAttendee->sig_timeslot_id)->exists()) {
            $reminder = SigTimeslotReminder::where('user_id', $sigAttendee->user_id)->where('timeslot_id', $sigAttendee->sig_timeslot_id)->first();
            $reminder->delete();
        }
    }


    public function restored(SigAttendee $sigAttendee) {
        //
    }

    public function forceDeleted(SigAttendee $sigAttendee) {
        //
    }
}
