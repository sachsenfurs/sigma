<?php

namespace App\Policies\Sig;

use App\Models\SigAttendee;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SigAttendeePolicy
{

    public function __construct() {

    }

    public function delete(User $user, SigAttendee $sigAttendee, ?Model $fromAttendee=null) {
        if($fromAttendee?->is_owner AND $fromAttendee?->sig_timeslot_id == $sigAttendee->sig_timeslot_id)
            return true;

        return false;
    }
}
