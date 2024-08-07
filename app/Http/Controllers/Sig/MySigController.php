<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigAttendee;
use App\Models\SigEvent;
use App\Models\SigFavorite;
use App\Models\SigHost;

class MySigController extends Controller
{
    public function show(SigEvent $sig) {
        $additionalInformations = [];
        $favs = 0;
        foreach($sig->timetableEntries as $entry) {
            $favs = $favs + SigFavorite::where('timetable_entry_id', $entry->id)->count();
            $timeslots = [];
            foreach($entry->sigTimeslots as $timeslot) {
                $timeslots[$timeslot->id] = SigAttendee::where('sig_timeslot_id', $timeslot->id)->get();
            }
            $additionalInformations[$entry->id] = [
                'favorites' => $favs,
                'timeslots' => $timeslots
            ];
        }

        return view("mysigs.show", compact([
            'sig',
            'additionalInformations',
        ]));
    }

    public function index() {
        $sighost = SigHost::where('reg_id', auth()->user()->reg_id)->first();
        // This check needs improvement
        if (!SigHost::where('reg_id', auth()->user()->reg_id)->first()) {
            return redirect()->back()->withErrors("Du hast keine Events die du veranstaltest!");
        }

        $sigs   = SigEvent::where('sig_host_id', $sighost->id)->orderBy("name", "ASC")->get();
        $additionalInformations = [];
        foreach($sigs as $sig) {
            $favs = 0;
            $attendees = 0;
            foreach($sig->timetableEntries as $entry) {
                $favs = $favs + SigFavorite::where('timetable_entry_id', $entry->id)->count();
                foreach($entry->sigTimeslots as $timeslot) {
                    $attendees = $attendees + SigAttendee::where('sig_timeslot_id', $timeslot->id)->count();
                }
            }
            $additionalInformations[$sig->id] = [
                'favorites' => $favs,
                'attendees' => $attendees,
            ];
        }

        return view("mysigs.index", compact([
            'sigs',
            'additionalInformations'
        ]));
    }

    public function toggleAttendeeList(SigEvent $sig) {
        // This check needs improvement
        $this->authorize("update", $sig);

        if ($sig->attendees_public) {
            $sig->attendees_public = false;
            return back()->withSuccess("Die Teilnehmerliste ist nun nicht mehr Öffentlich");
        } else {
            $sig->attendees_public = true;
            return back()->withSuccess("Die Teilnehmerliste ist nun nicht mehr Öffentlich");
        }
    }
}
