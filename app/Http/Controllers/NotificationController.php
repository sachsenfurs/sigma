<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request) {
        if($request->query("old"))
            $notifications = auth()->user()->notifications()->paginate(15)->withQueryString();
        else
            $notifications = auth()->user()->unreadNotifications()->paginate(15)->withQueryString();

        auth()->user()->unreadNotifications->markAsRead();

        return view("notifications.index", compact("notifications"));
    }

    public function update() {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', __('Notifications marked as read'));
    }
}
