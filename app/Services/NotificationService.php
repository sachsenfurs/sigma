<?php

namespace App\Services;

use App\Models\NotificationRoute;
use App\Models\Post\PostChannel;
use App\Models\User;
use App\Notifications\Notification;

class NotificationService
{
    /**
     * available channel
     */
    protected array $channels = ['mail', 'telegram'];

    /**
     * Notifications that will appear on the user settings page.
     * Will be determined by NotificationServiceProvider
     */
    protected array $userNotifications = [];
    protected array $adminNotifications = [];
    protected array $routableNotifications = [];


    /**
     * userNotifications = All notifications that can be configured by the user (sig reminders etc.)
     */
    public function registeruserNotifications(array $notifications): void {
        $this->userNotifications = array_unique(array_merge($notifications, $this->userNotifications));
    }

    /**
     * adminNotifications = All notifications related to administrative tasks (chat messages etc.)
     */
    public function registeradminNotifications(array $notifications): void {
        $this->adminNotifications = array_unique(array_merge($notifications, $this->adminNotifications));
    }

    /**
     * routableNotifications = All notifications that can be routed to specific users, roles and telegram channels (new sig application etc.)
     */
    public function registerroutableNotifications(array $notifications): void {
        $this->routableNotifications = array_unique(array_merge($notifications, $this->routableNotifications));
    }

    /**
     * returns array with [ 'custom_name' => CustomNotification::class, ... ]
     */
    public function getRoutableNotificationNames(): array {
        return array_map(fn($n) => $n::getName(), array_flip($this->routableNotifications));
    }


    protected function allNotifications(): array {
        return [...$this->userNotifications, ...$this->adminNotifications, ...$this->routableNotifications];
    }

    public function morphName(string $notificationClass): string {
        return $this->allNotifications()[$notificationClass] ?? $notificationClass;
    }

    public function resolveClass(string $name): ?string {
        return array_flip($this->allNotifications())[$name] ?? null;
    }

    /**
     * returns the user notifications for the user settings page (filter where $userSetting != false)
     */
    public function userNotifications(): array {
        $notifications = [];
        foreach($this->userNotifications AS $notificationClass => $notificationName) {
            if(class_exists($notificationClass) AND !empty($notificationClass::$userSetting)) {
                $notifications[$notificationClass] = $notificationName;
            }
        }
        return $notifications;
    }

    /**
     * used in App\Notifications\Notification class to determine the notification channel for each individual notification
     * verifies that telegram id is connected, otherwise remove it from channel
     */
    public function channels(Notification $notification, object $notifiable, array $additional = []): array {
        // check for notification routes
        if(method_exists($notifiable, "notificationRoutes")) {
            return $notifiable->notificationRoutes()->where("notification", $notification->getMorphName())->first()->channels ?? [];
        }

        if($notifiable instanceof User) {
            $indexName      = $this->userNotifications[$notification::class] ?? '';
            $channels       = $notifiable->notification_channels[$indexName] ?? [];
            $targetChannels = array_unique([...$channels, ...$additional]);

            // make sure telegram is connected
            if(!$notifiable->routeNotificationForTelegram())
                $targetChannels = array_diff($targetChannels, ['telegram']); // remove telegram

            return $targetChannels;
        }

        if($notifiable instanceof PostChannel) {
            return ['telegram'];
        }
        return [];
    }

    public function availableChannels(): array {
        return $this->channels;
    }

    /**
     * iterate through all current user notifications and enable telegram by default
     */
    public function enableTelegramNotifications(User $user): void {
        $channels = $user->notification_channels;
        foreach($this->userNotifications AS $notification => $name) {
            $channels[$name] = array_unique(['telegram', ...$channels[$name] ?? []]);
        }
        $user->notification_channels = $channels;
        $user->save();
    }

    /**
     * determine the recepients for the notification by looking up the notification routes
     */
    public function dispatchRoutedNotification(Notification $notification): void {
        $routes = NotificationRoute::where("notification", $notification->getMorphName())->get();
        foreach($routes AS $route) {
            $route->notifiable->notify($notification);
        }
    }

}
