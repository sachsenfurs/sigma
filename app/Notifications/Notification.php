<?php

namespace App\Notifications;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification as LaravelNotification;

class Notification extends LaravelNotification
{
    public static string $title = "Notification";

    public static function view($data) {
        return view("notifications.type.default", $data);
    }
}
