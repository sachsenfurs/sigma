<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\UnauthorizedException;
use App\Models\SigFavorite;
use App\Models\Chat;

class AjaxController extends Controller
{
    /**
     * Sets a favorite on an event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
            //$reminderAttributes = [
            //
            //];
            //auth()->user()->reminders()->create($reminderAttributes);
        }

        $response = [
            'status' => $result
        ];

        return response()->json($response);
    }

    public function refreshChat(Request $request, Chat $chat) {
        if (!$request->ajax()) {
            throw new UnauthorizedException();
        }

        $response = [
            'status'   => 'success',
            'messages' => $chat->messages()->count()
        ];

        return response()->json($response);
    }
}
