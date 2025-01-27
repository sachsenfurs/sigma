<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function edit() {
        $notificationType = [];
        foreach(NotificationService::$UserNotifications AS $notification => $name) {
            $notificationType[$name] = auth()->user()->notification_channels[$name] ?? [];
        }

        return view('user-settings.edit', compact('notificationType'));
    }

    public function update(Request $request) {
        $notifications = collect(NotificationService::$UserNotifications)->mapWithKeys(function($name) {
            return [$name => 'array|nullable'];
        });

        $validated = $request->validate($notifications->toArray());

        auth()->user()->update([
            'notification_channels' => $validated
        ]);

        return redirect()->back()->with('success', __('Settings saved!'));
    }
}
