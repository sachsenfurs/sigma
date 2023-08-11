<?php

namespace App\Http\Controllers\Sig;

use \Gate;
use App\Http\Controllers\Controller;
use App\Models\SigLocation;
use Illuminate\Http\Request;

class SigLocationController extends Controller
{
    public function index() {
        $locations = SigLocation::withCount("sigEvents")->having("sig_events_count", ">", 0)->get();
        if(auth()->user()?->can("manage_locations"))
            $locations = SigLocation::withCount("sigEvents")->get();

        return view("locations.index", compact("locations"));
    }

    public function show(SigLocation $location) {
        $events = $location->sigEvents()
                       ->with("timeTableEntries")
                       ->get();
        return view("locations.show", [
            'location' => $location,
            'events' => $events,
        ]);
    }

    public function edit(SigLocation $location) {
        Gate::authorize('manage_locations');

        return view("locations.createEdit", [
            'location' => $location,
        ]);
    }

    public function update(Request $request, SigLocation $location) {
        Gate::authorize('manage_locations');

        $validated = $request->validate([
            'name' => "required|string",
            'description' => "nullable|string",
            'render_ids' => "nullable|string",
        ]);
        $render_ids_new = $validated['render_ids'];
        $render_ids = explode(",", $render_ids_new);
        foreach($render_ids AS $index=>$render_id) {
            $render_ids[$index] = trim($render_id);
        }
        $validated['render_ids'] = $render_ids;

        $location->update($validated);

        return back()->withSuccess("Änderungen gespeichert");
    }

    public function create() {
        Gate::authorize('manage_locations');

        return view("locations.createEdit");
    }

    public function store(Request $request) {
        Gate::authorize('manage_locations');

        $validated = $request->validate([
            'name' => "required|string",
            'description' => "present",
            'render_ids' => "nullable|string",
        ]);
        $render_ids_new = $validated['render_ids'];
        $render_ids = explode(",", $render_ids_new);
        foreach($render_ids AS $index=>$render_id) {
            $render_ids[$index] = trim($render_id);
        }
        $validated['render_ids'] = $render_ids;

        // TODO:
        // FIXME

        if(!$validated['description'])
            $validated['description'] = "";

        SigLocation::create($validated);

        return back()->withSuccess("Location hinzugefügt!");
    }

    public function destroy(SigLocation $location) {
        Gate::authorize('manage_locations');

        if($location->sigEvents->count() > 0)
            return back()->withErrors("Es sind noch SIGs für diese Location eingetragen!");

        $location->delete();
        return redirect(route("locations.index"))->withSuccess("Location gelöscht");
    }
}
