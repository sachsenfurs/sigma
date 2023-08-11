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

        $entry = TimetableEntry::create($validated);

        if(is_array($request->get("time-start")) and is_array($request->get("reg-start"))) {

            foreach($request->get("time-start") AS $i=>$dateStart) {
                $timeStart = Carbon::parse($dateStart);
                $timeEnd = Carbon::parse($request->get("time-end")[$i]);
                $maxUsers = $request->get('max-users')[$i];
                $regStart = Carbon::parse($request->get('reg-start')[$i]);
                $regEnd = Carbon::parse($request->get('reg-end')[$i]);

                if($regStart === $regEnd) {
                    $regStart = Carbon::createFromDate($entry->start)->subDays(5);
                    $regEnd = $entry->start;
                }

                $entry->sigTimeslots()->create([
                    'slot_start' => $timeStart,
                    'slot_end' => $timeEnd,
                    'max_users' => $maxUsers,
                    'reg_start' => $regStart,
                    'reg_end' => $regEnd,
                ]);
            }
        }

        //return redirect(route("timetable.index"))->withSuccess("Eintrag erstellt!");
        return redirect()->action([TimetableController::class, 'edit'], ['entry' => $entry->id])->withSuccess("Eintrag erstellt!");

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
            'start' => 'required|date',
            'end' => 'required|date',
            'sig_location_id' => 'nullable|exists:' . SigLocation::class . ',id',
        ]);

        $validated['hide'] = $request->has("hide");
        $validated['cancelled'] = $request->has("cancelled");

        if($request->has("ignore_update"))
            $entry->timestamps = false;

        if($request->has("reset_update"))
            $entry->updated_at = $entry->created_at;


        $entry->update($validated);

        //dd($request);

        if(is_array($request->get("time-start")) and is_array($request->get("reg-start"))) {

            foreach($request->get("time-start") AS $i=>$dateStart) {
                $timeStart = Carbon::parse($dateStart);
                $timeEnd = Carbon::parse($request->get("time-end")[$i]);
                $maxUsers = $request->get('max-users')[$i];
                $regStart = Carbon::parse($request->get('reg-start')[$i]);
                $regEnd = Carbon::parse($request->get('reg-end')[$i]);

                if($regStart === $regEnd) {
                    $regStart = Carbon::createFromDate($entry->start)->subDays(7);
                    $regEnd = $entry->start;
                }

                $entry->sigTimeslots()->create([
                    'slot_start' => $timeStart,
                    'slot_end' => $timeEnd,
                    'max_users' => $maxUsers,
                    'reg_start' => $regStart,
                    'reg_end' => $regEnd,
                ]);
            }
        }

        return redirect()->route("timetable.index")->withSuccess(__("Saved changes!"));
    }

    public function destroy(TimetableEntry $entry) {
        $entry->delete();

        return redirect(route("timetable.index"))->withSuccess("Eintrag gelöscht");
    }
}
