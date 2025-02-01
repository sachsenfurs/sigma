<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\SigLocationResource;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Illuminate\Support\Facades\Auth;

class TimetableEntryController extends Controller
{

    public function index() {
//        $this->authorize("viewAny",TimetableEntry::class);
        return view("schedule.listview");
    }

    // Vue API
    public function timetableIndex() {
        $this->authorize("viewAny",TimetableEntry::class);

        $entries = TimetableEntry::public();
        if (!Auth::guest() && Auth::user()->canAccessPanel(\Filament\Facades\Filament::getPanel())) {
            $entries = TimetableEntry::withoutGlobalScope('public');
        }

        $entries = $entries
             ->with("sigLocation")
             ->with("sigEvent", function($query) {
                 return $query->with(["sigHosts", "sigHosts.user"])
                 ->with("sigTags");
             })
             ->with("favorites", function ($query) {
                 return $query->where("user_id", auth()->id());
             })
             ->orderBy("start")
             ->get();

        return ScheduleResource::collection($entries);
    }

    public function show(TimetableEntry $entry) {
        $this->authorize("view", $entry);

        $entry->load([
            "sigEvent.sigHosts",
            "sigEvent.forms",
            "sigEvent.timetableEntries.sigLocation",
        ]);

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
