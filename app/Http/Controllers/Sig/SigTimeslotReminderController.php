<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigTimeslot;
use App\Models\SigTimeslotReminder;
use Illuminate\Http\Request;

class SigTimeslotReminderController extends Controller
{
    /**
     * Sets a reminder on an event.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'timeslot_id' => 'required|exists:sig_timeslots,id',
            'minutes_before'      => 'required|numeric|min:15|max:60'
        ]);

        $sigTimeslot = SigTimeslot::find($validated['timeslot_id']);

        $attributes = [
            'timeslot_id' => $sigTimeslot->id,
            'minutes_before' => $validated['minutes_before'],
            'user_id' => auth()->user()->id,
            'send_at' => strtotime($sigTimeslot->start) - ($validated['minutes_before'] * 60)
        ];

        if (!SigTimeslotReminder::where('user_id', auth()->user()->id)->where('timeslot_id', $attributes['timeslot_id'])->exists()) {
            if (auth()->user()->timeslotReminders()->create($attributes)) {
                return redirect()->back()->withSuccess(__('Reminder successfully created!'));
            }
        } else {
            return redirect()->back()->withErrors(__('This reminder already exists!'));
        }
    }
    
    /**
     * Sets a reminder on an event.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $attributes = $request->validate([
            'timeslot_id' => 'required|exists:timetable_entries,id',
            'minutes_before'      => 'required|numeric|min:15|max:60'
        ]);

        $result = 'error';

        $timetableEntry = SigTimeslot::find($attributes['timeslot_id']);

        $attributes['send_at'] = strtotime($timetableEntry->start) - ($attributes['minutes_before'] * 60);

        

        if (SigTimeslotReminder::where('user_id', auth()->user()->id)->where('timeslot_id', $attributes['timeslot_id'])->exists()) {
            $reminder = SigTimeslotReminder::where('user_id', auth()->user()->id)->where('timeslot_id', $attributes['timeslot_id'])->first();
            $reminder->update($attributes);
            return redirect()->back()->withSuccess(__('Reminder successfully updated!'));
        } else {
            return redirect()->back()->withErrors(__('This reminder doesn\'t exists!'));
        }
    }
}
