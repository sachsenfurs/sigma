<?php

namespace App\Http\Controllers\Sig;

use \Gate;
use App\Http\Controllers\Controller;
use App\Models\SigLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SigLocationController extends Controller
{
    public function index() {
        $locations = [];
        if(Gate::allows("viewAny", SigLocation::class))
            $locations = SigLocation::with(["sigEvents" => fn($query) => $query->public()])
                ->used()
                ->orderBy("name")
                ->get();

        return view("locations.index", compact("locations"));
    }

    public function show(SigLocation $location) {
        $this->authorize("view", $location);

        $sigEvents = Cache::remember("sigLocationShow{$location->id}", 120, function() use ($location) {
            return $location
                ->sigEvents()
                ->public()
                ->with("timetableEntries")
                ->with("sigHosts")
                ->get();
        });

        return view("locations.show", [
            'location' => $location,
            'sigEvents' => $sigEvents,
        ]);
    }
}
