<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;

class SigFavoriteController extends Controller
{
    public function store(Request $request) {
        $attributes = $request->validate([
            'timetable_entry_id' => 'required|exists:timetable_entries,id'
        ]);

        auth()->user()->favorites()->create([
            'timetable_entry_id' => $attributes['timetable_entry_id'],
            'user_id' => auth()->user()->id,
        ]);
    }


    public function destroy(Request $request, TimetableEntry $entry) {
        auth()->user()->favorites()->where("timetable_entry_id", $entry->id)->get()->each->delete(); // don't delete via eloquent because otherwise the observer won't be called

        if(!$request->ajax())
            return back()->withSuccess(__('Favorite successfully removed!'));
    }
}
