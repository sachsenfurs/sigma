<?php

namespace App\Http\Controllers;

use App\Models\SigEvent;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use App\Models\SigTimeslot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TimetableController extends Controller
{

    public function index() {
        $entriesPerDay = TimetableEntry::orderBy('start', "ASC")->get()
            ->groupBy(function($item, $key) {
                return Carbon::parse($item['start'])->format("Y-m-d");
        });

        $sigEvents = SigEvent::orderBy("name")->get();
        $sigLocations = SigLocation::orderBy("name")->get();

        return view("timetable.index", compact([
            'entriesPerDay',
            'sigEvents',
            'sigLocations',
        ]));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'sig_event_id' => "required|exists:" . SigEvent::class . ",id",
            'sig_location_id' => "nullable|exists:" . SigLocation::class . ",id",
            'start' => "required|date",
            'end' => "required|date",
        ]);

        TimetableEntry::create($validated);

        return redirect(route("timetable.index"))->withSuccess("Timeslot eingetragen!");
    }

    public function edit(TimetableEntry $entry) {
        $locations = SigLocation::all();

        return view("timetable.edit", [
            'entry' => $entry,
            'locations' => $locations
        ]);
    }

    public function update(Request $request, TimetableEntry $entry) {
        $validated = $request->validate([
            'start' => "required|date",
            'end' => "required|date",
            'sig_location_id' => "nullable|exists:" . SigLocation::class . ",id",
        ]);

        $validated['hide'] = $request->has("hide");
        $validated['cancelled'] = $request->has("cancelled");

        if($request->has("ignore_update"))
            $entry->timestamps = false;

        if($request->has("reset_update"))
            $entry->updated_at = $entry->created_at;


        $entry->update($validated);

        return back()->withSuccess("Änderungen gespeichert!");
    }

    public function destroy(TimetableEntry $entry) {
        $entry->delete();

        return redirect(route("timetable.index"))->withSuccess("Timeslot gelöscht");
    }
}
