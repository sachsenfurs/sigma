<?php

namespace App\Http\Controllers\Sig;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SigTimeslot;
use Redirect;

class SigRegistrationController extends Controller
{
    public function index() {
        $sigs = auth()->user()->attendeeEvents->all();

        return view("registration.index", compact("sigs"));
    }

    public function register(SigTimeslot $timeslot) {
        if($timeslot->max_users < $timeslot->sigAttendees->count()) {
            return redirect()->back()->with('error', 'Dieser Timeslot ist bereits voll!');
        } elseif(auth()->user()->attendeeEvents->has(auth()->user()->id)) {
            /* TODO =>  Muss noch gefixt werden */
            return redirect()->back()->with('error', 'Du nimmst bereits an diesem Timeslot teil!');
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
