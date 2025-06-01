<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Facades\NotificationService;
use App\Models\UserCalendar;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function edit() {
        $notificationType = [];
        foreach(NotificationService::userNotifications() AS $notification => $name) {
            $notificationType[$name] = [
                'name' => NotificationService::resolveClass($name)::getName(),
                'channels' => auth()->user()->notification_channels[$name] ?? [],
            ];
        }

        $calendars = auth()->user()->calendars;

        return view('user-settings.edit', compact('notificationType', 'calendars'));
    }

    public function update(Request $request) {
        $notifications = collect(NotificationService::userNotifications())->mapWithKeys(function($name) {
            return [$name => 'array|nullable'];
        });

        $validated = $request->validate($notifications->toArray());

        auth()->user()->update([
            'notification_channels' => $validated
        ]);

        return redirect()->back()->with('success', __('Settings saved!'));
    }
}
