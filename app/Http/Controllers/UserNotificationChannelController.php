<?php

namespace App\Http\Controllers;

use App\Models\UserNotificationChannel;
use Illuminate\Http\Request;

class UserNotificationChannelController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $notificationType = [
            'sig_favorite_reminder'            => UserNotificationChannel::get('sig_favorite_reminder', 'telegram'),
            'sig_timeslot_reminder'            => UserNotificationChannel::get('sig_timeslot_reminder', 'telegram'),
            'timetable_entry_cancelled'        => UserNotificationChannel::get('timetable_entry_cancelled', 'telegram'),
            'timetable_entry_location_changed' => UserNotificationChannel::get('timetable_entry_location_changed', 'telegram'),
            'timetable_entry_time_changed'     => UserNotificationChannel::get('timetable_entry_time_changed', 'telegram')
        ];

        return view('user-settings.edit', compact('notificationType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if (\Str::startsWith($key, 'notification_')) {
                UserNotificationChannel::set(\Str::substr($key, 13), $value);
            }
        }

        return redirect()->back()->with('success', 'Setting saved!');
    }
}
