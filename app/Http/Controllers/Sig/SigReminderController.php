<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigReminder;
use App\Models\TimetableEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SigReminderController extends Controller
{
    /**
     * Sets a reminder on an event.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setReminder(Request $request)
    {
        $attributes = $request->validate([
            'timetable_entry_id' => 'required|exists:timetable_entries,id',
            'minutes_before'      => 'required|numeric|min:15|max:60'
        ]);

        $result = 'error';

        $timetableEntry = TimetableEntry::find($attributes['timetable_entry_id']);

        $attributes['send_at'] = strtotime($timetableEntry->start) - ($attributes['minutes_before'] * 60);

        if (!SigReminder::where('user_id', auth()->user()->id)->where('timetable_entry_id', $attributes['timetable_entry_id'])->exists()) {
            if (auth()->user()->reminders()->create($attributes)) {
                $result = 'success';
            }
        }

        return redirect()->back()->withSuccess(__('Reminder successfully created!'));
    }
}
