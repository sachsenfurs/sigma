<?php

namespace App\Http\Controllers;

use App\Models\UserNotificationChannel;
use Illuminate\Http\Request;
use \Str;

class UserNotificationChannelController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $notificationType = [
            'sig_favorite_reminder'            => UserNotificationChannel::get('sig_favorite_reminder', 'email'),
            'sig_timeslot_reminder'            => UserNotificationChannel::get('sig_timeslot_reminder', 'email'),
            'timetable_entry_cancelled'        => UserNotificationChannel::get('timetable_entry_cancelled', 'email'),
            'timetable_entry_location_changed' => UserNotificationChannel::get('timetable_entry_location_changed', 'email'),
            'timetable_entry_time_changed'     => UserNotificationChannel::get('timetable_entry_time_changed', 'email'),
            'chat_new_message'                 => UserNotificationChannel::get('chat_new_message', 'email')
        ];

        return view('user-settings.edit', compact('notificationType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $notifications = [];

        foreach ($request->all() as $key => $value) {
            if (!Str::startsWith($key, 'notification-')) {
                continue;
            }

            if ($value == 1) {
                $data = explode('-', $key);
                $notifications[$data[2]][] = $data[1];
            }
        }

        foreach ($notifications as $notification => $value) {
            UserNotificationChannel::set($notification, json_encode($value));
        }

        return redirect()->back()->with('success', 'Setting saved!');
    }
}
