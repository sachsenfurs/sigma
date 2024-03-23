<?php

namespace App\Http\Controllers\Sig;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\SigAttendee;
use App\Models\SigTimeslot;
use Illuminate\Support\Carbon;

class SigRegistrationController extends Controller
{
    public function index() {
        $sigs = auth()->user()->attendeeEvents->all();

        return view("registration.index", compact("sigs"));
    }

    public function register(SigTimeslot $timeslot) {
        $userId = request()->input('regNumber', auth()->user()->id);

        if (!User::find($userId)) {
            return redirect()->back()->with('error', 'Ungültige Registrierungsnummer!');
        }

        $currentTime = strtotime(Carbon::now()->toDateTimeString());
        $regStart = strtotime($timeslot->reg_start);
        $regEnd = strtotime($timeslot->reg_end);
        if ($timeslot->timetableEntry->maxUserRegsExeeded()) {
            return redirect()->back()->with('error', 'Maximale Anzahl an Registrierungen für diesen Tag für dieses Event erreicht!');
        }
        if ($timeslot->max_users <= $timeslot->sigAttendees->count()) {
            return redirect()->back()->with('error', 'Dieser Timeslot ist bereits voll!');
        } elseif($timeslot->sigAttendees->contains('user_id', $userId)) {
            return redirect()->back()->with('error', 'Du nimmst bereits an diesem Timeslot teil!');
        } elseif($regStart > $currentTime) {
            return redirect()->back()->with('error', 'Du kannst dich noch nicht registrieren!');
        } elseif($regEnd < $currentTime) {
            return redirect()->back()->with('error', 'Die Registrierung für dieses Event ist nicht mehr verfügbar!');
        } else {
            $timeslot->sigAttendees()->create(['user_id' => $userId]);
            //auth()->user()->reminders->create(['timetable_entry_id']);
            return redirect()->back()->with('success', 'Erfolgreich für den Timeslot registriert!');
        }
    }

    public function cancel(SigTimeslot $timeslot) {
        //$timeslot->sigAttendees->forget(['user_id' => auth()->user()->id]);
        SigAttendee::where(['user_id' => auth()->user()->id, 'sig_timeslot_id' => $timeslot->id])->first()->delete();

        return redirect()->back()->with('success', 'Registrierung erfolgreich gelöscht!');
    }
}
