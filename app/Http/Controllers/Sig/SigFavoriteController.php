<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SigFavoriteController extends Controller
{
    public function store(Request $request) {
        Gate::allowIf(Auth::check());

        $attributes = $request->validate([
            'timetable_entry_id' => 'required|exists:timetable_entries,id'
        ]);

        auth()->user()->favorites()->create([
            'timetable_entry_id' => $attributes['timetable_entry_id'],
            'user_id' => auth()->user()->id,
        ]);

        $reminderAttributes = [
            'minutes_before' => 15,
        ];

        auth()->user()->reminders()->make()->remindable()->associate(TimetableEntry::find($attributes['timetable_entry_id']))->save();
    }


    public function destroy(Request $request, TimetableEntry $entry) {
        Gate::allowIf(Auth::check());

        auth()->user()->favorites()->where("timetable_entry_id", $entry->id)->delete();

        if(!$request->ajax())
            return back()->withSuccess(__('Favorite successfully removed!'));
    }
}
