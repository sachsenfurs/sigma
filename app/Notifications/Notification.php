<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification as LaravelNotification;

abstract class Notification extends LaravelNotification
{
    public static string $title = "Notification";
    public static bool $userSetting = true;

    public static function view($data) {
        return view("notifications.type.default", $data);
    }

}
