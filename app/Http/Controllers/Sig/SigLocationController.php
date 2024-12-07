<?php

namespace App\Http\Controllers\Sig;

use \Gate;
use App\Http\Controllers\Controller;
use App\Models\SigLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SigLocationController extends Controller
{
    public function index() {
        $locations = [];
        if(Gate::allows("viewAny", SigLocation::class))
            $locations = SigLocation::with("sigEvents")
                ->whereHas("timetableEntries", fn(Builder $query) => $query->where("hide", false))
                ->orderBy("name")
                ->get();

        return view("locations.index", compact("locations"));
    }

    public function show(SigLocation $location) {
        $this->authorize("view", $location);

        $sigEvents = $location
            ->sigEvents()
            ->public()
            ->with("timetableEntries")
            ->with("sigHosts")
            ->get();
        return view("locations.show", [
            'location' => $location,
            'sigEvents' => $sigEvents,
        ]);
    }
}
