<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TimetableController;
use App\Models\TimeTableEntry;
use App\Models\SigTimeslot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SigTimeslotController extends Controller
{
    public function create() {
        return view("timeslots.create");
    }

    public function store(Request $request) {
        //dd($request);
        $validated = $request->validate([
            'slot_start' => 'required',
            'slot_end' => 'required',
            'reg_start' => '',
            'reg_end' => '',
            'max_users' => 'integer',
            'timetable_entry_id' => 'integer',
        ]);

        if($validated['max_users'] <= 0) {
            $validated['max_users'] = 1;
        }

        $validated['slot_start'] = Carbon::parse($request->get('slot_start'));
        $validated['slot_end'] = Carbon::parse($request->get('slot_end'));

        SigTimeslot::create($validated);

        return back()->withSuccess("Timeslot erstellt!");
    }

    public function edit(SigTimeslot $timeslot) {
        return view("timeslots.edit", compact('timeslot'));
    }

    public function update(Request $request, SigTimeslot $timeslot) {
        $validated = $request->validate([
            'slot_start' => 'required',
            'slot_end' => 'required',
            'reg_start' => 'required|date',
            'reg_end' => 'required|date',
            'max_users' => 'integer',
        ]);

        if($validated['max_users'] <= 0) {
            $validated['max_users'] = 1;
        }

        $validated['slot_start'] = Carbon::parse($request->get('slot_start'));
        $validated['slot_end'] = Carbon::parse($request->get('slot_end'));

        $timeslot->update($validated);
        return redirect()->action([TimetableController::class, 'edit'], ['entry' => $timeslot->timetableEntry->id])->withSuccess("Änderungen gespeichert!");
    }

    public function destroy(SigTimeslot $timeslot) {
        if ($timeslot->sigAttendees->count() == 0)
        {
            $timeslot->delete();

            return redirect()->back()->withSuccess("Timeslot gelöscht");
        }
        return redirect()->back()->withErrors("Timeslot konnte nicht gelöscht werden, da noch user dem Timeslot zugewiesen sind!");
    }
}
