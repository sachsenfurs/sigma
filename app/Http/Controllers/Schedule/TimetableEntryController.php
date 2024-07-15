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
        $this->authorize("viewAny",SigLocation::class);

        $locations = SigLocation::used()->withCount("sigEvents")
            ->used()
            ->where('essential', false)
            ->get();
        $locations->each(function($location) {
            $location->title = $location->name;
            if ($location->description != $location->name) {
                $location->title .= " - $location->description";
            }
        });
        // Show only necessary information
        $locations->setVisible([
            'id',
            'title',
        ]);
        return collect($locations);
    }

    public function calendarEvents() {
        $this->authorize("viewAny",TimetableEntry::class);

        $entries = TimetableEntry::public()->with("sigEvent")->orderBy("start")->get();
        $entries->each(function($timetableEntry) {
            $timetableEntry->title = $timetableEntry->sigEvent->name_localized;
            $timetableEntry->resourceId = $timetableEntry->sig_location_id;
            $timetableEntry->sig_event = [
                'name_localized' => $timetableEntry->sigEvent->name_localized,
                'description_localized' => $timetableEntry->sigEvent->description_localized,
            ];
        });
        // Show only necessary information
        $entries->setVisible([
            'id',
            'resourceId',
            'title',
            'start',
            'sig_event',
            'end'
        ]);
        return collect($entries);
    }
}
