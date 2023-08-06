<?php

namespace App\Http\Controllers\Sig;

use App\Models\SigEvent;
use App\Models\SigHost;
use App\Http\Controllers\Controller;
use App\Models\SigAttendee;
use App\Models\SigFavorite;
use App\Models\SigTimeslot;
use DB;
use Gate;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class SigMyEventController extends Controller
{
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
        if ($sig->sigHost->reg_id != auth()->user()->reg_id || !Gate::authorize('manage_events')) {
            return redirect()->back()->withErrors("Du kannst dieses Event nicht bearbeiten!");
        }

        if ($sig->attendees_public) {
            $sig->attendees_public = false;
            return back()->withSuccess("Die Teilnehmerliste ist nun nicht mehr Öffentlich");
        } else {
            $sig->attendees_public = true;
            return back()->withSuccess("Die Teilnehmerliste ist nun nicht mehr Öffentlich");
        }
    }
}
