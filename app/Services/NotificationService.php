<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class NotificationService
{
    /**
     * Notifications that will appear on the user settings page.
     * Will be determined by NotificationServiceProvider
     */
    public static array $UserNotifications = [];

    public static function channels(Notification $notification, Model $notifiable, array $additional = []): array {
        if($notifiable instanceof User) {
            $indexName = self::$UserNotifications[$notification::class] ?? '';
            $channels = $notifiable->notification_channels[$indexName] ?? [];
            return array_unique([...$channels, ...$additional]);
        }
        return [];
    }

    public static function enableTelegramNotifications(User $user): void {
        $channels = $user->notification_channels;
        foreach(self::$UserNotifications AS $notification => $name) {
            $channels[$name] = array_unique(["telegram", ...$channels[$name] ?? []]);
        }

        $user->notification_channels = $channels;
        $user->save();
    }

}
