<?php

namespace App\Http\Controllers\Sig;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SigTimeslot;
use Redirect;
use Illuminate\Support\Carbon;

class SigRegistrationController extends Controller
{
    public function index() {
        $sigs = auth()->user()->attendeeEvents->all();

        return view("registration.index", compact("sigs"));
    }

    public function register(SigTimeslot $timeslot) {
        $currentTime = strtotime(Carbon::now()->toDateTimeString());
        $regStart = strtotime($timeslot->reg_start);
        $regEnd = strtotime($timeslot->reg_end);
        if($timeslot->max_users <= $timeslot->sigAttendees->count()) {
            return redirect()->back()->with('error', 'Dieser Timeslot ist bereits voll!');
        } elseif($timeslot->sigAttendees->contains('user_id', auth()->user()->id)) {
            return redirect()->back()->with('error', 'Du nimmst bereits an diesem Timeslot teil!');
        } elseif($regStart > $currentTime) {
            return redirect()->back()->with('error', 'Du kannst dich noch nicht registrieren!');
        } elseif($regEnd < $currentTime) {
            return redirect()->back()->with('error', 'Die Registrierung für dieses Event ist nicht mehr verfügbar!');
        } else {
            $timeslot->sigAttendees()->create(['user_id' => auth()->user()->id]);
            return redirect()->back()->with('success', 'Erfolreich für den Timeslot registriert!');
        }
    }

    public function cancel(SigTimeslot $timeslot) {
        $timeslot->sigAttendees()->delete(['user_id' => auth()->user()->id]);

       return redirect()->back()->with('success', 'Registrierung erfolgreich gelöscht!');
    }
}
