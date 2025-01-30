<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NotificationService
{
    /**
     * Notifications that will appear on the user settings page.
     * Will be determined by NotificationServiceProvider
     */
    protected static array $Notifications = [];

    public static function registerNotification(array $notification): void {
        self::$Notifications = array_unique(array_merge($notification));
    }

    public static function userNotifications(): array {
        $userNotifications = [];
        foreach(self::$Notifications AS $notificationClass => $notificationName) {
            if(class_exists($notificationClass) AND !empty($notificationClass::$userSetting)) {
                $userNotifications[$notificationClass] = $notificationName;
            }
        }
        return $userNotifications;
    }

    public static function channels(Notification $notification, object $notifiable, array $additional = []): array {
        if($notifiable instanceof User) {
            $indexName = self::$Notifications[$notification::class] ?? '';
            $channels = $notifiable->notification_channels[$indexName] ?? [];

            // make sure telegram is connected
            if(!$notifiable->telegram_user_id AND isset($channels['telegram']) )
                unset($channels['telegram']);

            return array_unique([...$channels, ...$additional]);
        }
        return [];
    }

    public static function enableTelegramNotifications(User $user): void {
        $channels = $user->notification_channels;
        foreach(self::$Notifications AS $notification => $name) {
            $channels[$name] = array_unique(["telegram", ...$channels[$name] ?? []]);
        }

        $user->notification_channels = $channels;
        $user->save();
    }

}
