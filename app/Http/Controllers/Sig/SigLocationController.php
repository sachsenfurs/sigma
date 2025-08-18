<?php

namespace App\Http\Controllers\Sig;

use \Gate;
use App\Http\Controllers\Controller;
use App\Models\SigLocation;
use Illuminate\Support\Facades\Cache;

class SigLocationController extends Controller
{
    public function index() {
        $locations = collect();
        if(Gate::allows("viewAny", SigLocation::class))
            $locations = SigLocation::with(["sigEvents" => fn($query) => $query->public(), "sigEvents.timetableEntries"])
                ->used()
                ->orderBy("name")
                ->get();

        return view("locations.index", compact("locations"));
    }

    public function show(SigLocation $location) {
        $this->authorize("view", $location);

        $sigEvents = Cache::remember("sigLocationShow{$location->id}", app()->hasDebugModeEnabled() ? 1 : 120, function() use ($location) {
            return $location
                ->sigEvents()
                ->public()
                ->with(["timetableEntries", "sigHosts"])
                ->orderByRaw("timetable_entries.start")
                ->get();
        });

        return view("locations.show", [
            'location' => $location,
            'sigEvents' => $sigEvents,
        ]);
    }
}
