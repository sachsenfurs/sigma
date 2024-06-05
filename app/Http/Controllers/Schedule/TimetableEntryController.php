<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
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
}
