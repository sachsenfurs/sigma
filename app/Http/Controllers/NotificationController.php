<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->unreadNotifications;
        
        return view("notifications.index", compact("notifications"));
    }

    /**
     * Update the specified resources in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', __('Notifications marked as read'));
    }
}
