<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\SigLocationResource;
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
                 return $query->with("sigHosts")
                 ->with("sigTags");
             })
             ->orderBy("start")
             ->get();

        return ScheduleResource::collection($entries);
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
            ->get();

        return SigLocationResource::collection($locations);
    }

}
