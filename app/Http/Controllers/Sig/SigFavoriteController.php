<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

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
}
