<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\UnauthorizedException;
use App\Models\SigFavorite;
use App\Models\SigReminder;
use App\Models\TimetableEntry;
use Redirect;

class AjaxController extends Controller
{
    /**
     * Sets a favorite on an event.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setFavorite(Request $request)
    {
        if (!$request->ajax()) {
            throw new UnauthorizedException();
        }

        $attributes = $request->validate([
            'timetable_entry_id' => 'required|exists:timetable_entries,id'
        ]);

        $result = 'error';

        if (!SigFavorite::select('*')->where('user_id', auth()->user()->id)->where('timetable_entry_id', $attributes['timetable_entry_id'])->exists()) {
            if (auth()->user()->favorites()->create($attributes)) {
                $result = 'success';
            }
        }

        $response = [
            'status' => $result
        ];

        return response()->json($response);
    }
}
