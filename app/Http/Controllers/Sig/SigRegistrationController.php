<?php

namespace App\Http\Controllers\Sig;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\SigAttendee;
use App\Models\SigTimeslot;
use Illuminate\Support\Carbon;

class SigRegistrationController extends Controller
{

    public function register(SigTimeslot $timeslot) {
        $regId = request()->input('regId', auth()->user()->reg_id);
        $user = User::where('reg_id', $regId)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Ungültige Registrierungsnummer!');
        }

        $currentTime = strtotime(Carbon::now()->toDateTimeString());
        $regStart = strtotime($timeslot->reg_start);
        $regEnd = strtotime($timeslot->reg_end);

        /*
         *  ToDo:
         * - Add Possibility to Event to register more users per timeslot for master attendee
         * -
         *
         */

        if ($timeslot->timetableEntry->maxUserRegsExeeded($user)) {
            // Check if max registrations per day limit is reached
            return redirect()->back()->with('error', 'Maximale Anzahl an Registrierungen für diesen Tag für dieses Event erreicht!');
        }

        if(!$timeslot->self_register)
            return redirect()->back();
        if ($timeslot->max_users <= $timeslot->sigAttendees->count()) {
            // Check if registration slots are available
            return redirect()->back()->with('error', 'Dieser Timeslot ist bereits voll!');

        } elseif($timeslot->sigAttendees->contains('user_id', $user->id)) {
            // Check if user already attends event
            return redirect()->back()->with('error', 'Du nimmst bereits an diesem Timeslot teil!');

        } elseif($regStart > $currentTime) {
            // Check if user tries to register to early
            return redirect()->back()->with('error', 'Du kannst dich noch nicht registrieren!');

        } elseif($regEnd < $currentTime) {
            // Check if the registration timeframe has expired
            return redirect()->back()->with('error', 'Die Registrierung für dieses Event ist nicht mehr verfügbar!');

        } else {
            // Register attendee for event
            $timeslot->sigAttendees()->create(['user_id' => $user->id]);
            return redirect()->back()->with('success', 'Erfolgreich für den Timeslot registriert!');

        }
    }

    public function cancel(SigTimeslot $timeslot) {
        $regId = request()->input('regId', auth()->user()->reg_id);
        $user = User::where('reg_id', $regId)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Ungültige Registrierungsnummer!');
        }

        SigAttendee::where(['user_id' => $user->id, 'sig_timeslot_id' => $timeslot->id])->first()->delete();

        return redirect()->back()->with('success', 'Registrierung erfolgreich gelöscht!');
    }
}
