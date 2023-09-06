<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SigFavoriteController extends Controller
{
    /**
     * Removes a favorite on an event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeFavorite(Request $request)
    {
        $attributes = $request->validate([
            'timetable_entry_id' => 'required|exists:timetable_entries,id'
        ]);

        auth()->user()->favorites->where('timetable_entry_id', $attributes['timetable_entry_id'])->first()->delete();

        return redirect()->back()->withSuccess(__('Favorite successfully removed!'));
    }

    public function store(Request $request) {
        $this->authorize("login");

        $attributes = $request->validate([
            'timetable_entry_id' => 'required|exists:timetable_entries,id'
        ]);

        auth()->user()->favorites()->create([
            'timetable_entry_id' => $attributes['timetable_entry_id'],
            'user_id' => auth()->user()->id,
        ]);

        $reminderAttributes = [
            'timetable_entry_id' => $attributes['timetable_entry_id'],
            'minutes_before' => 15,
            'send_at' => strtotime(TimetableEntry::find(['id' => $attributes['timetable_entry_id']])->first()->start) - (15 * 60),
        ];
        
        auth()->user()->reminders()->create($reminderAttributes);

    }
}
