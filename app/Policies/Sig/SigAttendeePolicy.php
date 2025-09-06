<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\SigAttendee;
use App\Models\SigTimeslot;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class SigAttendeePolicy extends ManageEventPolicy
{

    // con-ops check
    public function adminCreate(User $user, ?SigTimeslot $timeslot=null): bool {
        // allow for con-ops
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        if($timeslot) {
            if($timeslot->isFull())
                return false;

            if($timeslot->isWithinRegTime())
                return true;
        }
        return false;
    }

    public function create(User $user, ?SigTimeslot $timeslot=null): bool|Response {
        if(!$timeslot)
            return false;

        // only allow within registration period
        if($timeslot->reg_start->isFuture())
            return Response::deny(__('Registration opens')." ".$timeslot->reg_start->diffForHumans());
        if($timeslot->reg_end->isPast())
            return Response::deny(__('Registration period is over'));

        // Check if registration slots are available
        if($timeslot->isFull())
            return Response::deny(__('Slot is full'));

        // Check if user already attends event
        if($timeslot->sigAttendees->contains('user_id', $user->id))
            return Response::deny(__('You are already participating in this slot!'));

        // Check if max registrations per day limit is reached
        if($timeslot->timetableEntry->maxUserRegsExeeded($user))
            return Response::deny(__('Maximum number of registrations for this day and for this event reached!'));

        // is self-registration enabled?
        if(!$timeslot->self_register)
            return false;

        return true;
    }

    public function delete(User $user, SigAttendee $sigAttendee, ?Model $fromAttendee=null): bool {
        // allow for con-ops
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        if($sigAttendee->sigTimeslot->isWithinRegTime()) {
            // allow slot owner to remove others
            if($sigAttendee->sigTimeslot->group_registration) {
                if($fromAttendee?->is_owner AND $fromAttendee?->sig_timeslot_id == $sigAttendee->sig_timeslot_id) {
                    return true;
                }
            }

            // allow cancellation of own slots
            if($sigAttendee->user_id == $user->id)
                return true;
        }


        return false;
    }
}
