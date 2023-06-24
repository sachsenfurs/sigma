<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\TimeTableEntry;
use App\Models\SigTimeslot;
use Illuminate\Http\Request;

class SigTimeslotController extends Controller
{
    public function create() {
        return view("timeslots.create");
    }

    public function store(Request $request) {

    }

    public function edit(SigTimeslot $timeslot) {
        return view("timeslots.edit", compact('timelot'));
    }

    public function update(Request $request, SigTimeslot $timeslot) {
        $validated = $request->validate([
            'slot_start' => 'required|date',
            'slot_end' => 'required|date',
            'reg_start' => 'required|date',
            'reg_end' => 'required|date',
            'max_users' => 'integer',
        ]);
        
        if($validated['max_users'] <= 0) {
            $validated['max_users'] = 1;
        }

        $timeslot->update($validated);
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
