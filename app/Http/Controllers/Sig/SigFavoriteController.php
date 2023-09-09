<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SigFavoriteController extends Controller
{
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


    public function destroy(Request $request, TimetableEntry $entry) {
        $this->authorize("login");

        auth()->user()->favorites()->where("timetable_entry_id", $entry->id)->delete();

        if(!$request->ajax())
            return back()->withSuccess(__('Favorite successfully removed!'));
    }
}
