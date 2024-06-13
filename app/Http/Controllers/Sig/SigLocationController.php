<?php

namespace App\Http\Controllers\Sig;

use \Gate;
use App\Http\Controllers\Controller;
use App\Models\SigLocation;
use Illuminate\Http\Request;

class SigLocationController extends Controller
{
    public function index() {
        $this->authorize("viewAny", SigLocation::class);

        $locations = SigLocation::withCount("sigEvents")->having("sig_events_count", ">", 0)->get();

        if(!Gate::authorize("manage_locations"))
            $locations = [];

        return view("locations.index", compact("locations"));
    }

    public function show(SigLocation $location) {
        $this->authorize("view", $location);

        $events = $location->sigEvents()->public()->get();
        return view("locations.show", [
            'location' => $location,
            'events' => $events,
        ]);
    }
}
