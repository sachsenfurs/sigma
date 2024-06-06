<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\SigLocation;
use App\Models\TimetableEntry;

class TimetableEntryController extends Controller
{

    public function index() {
//        $this->authorize("viewAny",TimetableEntry::class);
        return view("schedule.listview");
    }

    // Vue API
    public function timetableIndex() {
        $this->authorize("viewAny",TimetableEntry::class);

        $entries = TimetableEntry::public()
             ->with("sigLocation")
             ->with("sigEvent", function($query) {
                 return $query->with("sigHost")
                     ->with("sigTags");
             })
             ->orderBy("start")
             ->get();

        // remove unnecessary information
        foreach($entries AS $entry) {
            $entry->sigLocation->setVisible([
                'id',
                'name',
                'description',
                'name_localized'
            ]);
        }
        return $entries;
    }

    public function show(TimetableEntry $entry) {
        $this->authorize("view", $entry);

        return view("schedule.show", [
            'entry' => $entry,
        ]);
    }


    public function table() {
        return view("schedule.tableview");
    }

    public function calendar() {
        return view("schedule.calendarview");
    }

    public function calendarResources() {
        $locations = SigLocation::withCount("sigEvents")
            ->having('sig_events_count', '>', 0)
            ->groupBy('name')
            ->orderByRaw('FIELD(sig_locations.id, 27,13,11,22,10,5,15,14,2,21,20,19,18,7,6,25,1) DESC')
            ->get([
                'id',
                'name',
            ]);
        $locations->each(function($location) {
            $location->title = $location->name;
        });
        return collect($locations);
    }

    public function calendarEvents() {
        $entries = TimetableEntry::public()->orderBy("start")->get();
        $entries->each(function($timetableEntry) {
            $timetableEntry->title = $timetableEntry->sigEvent->name_localized;
            $timetableEntry->resourceId = $timetableEntry->sig_location_id;
        });
        return collect($entries);
    }
}
