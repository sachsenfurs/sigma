<?php

namespace App\Notifications;

use App\Facades\NotificationService;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Notification;

class MorphedDatabaseChannel extends DatabaseChannel
{
    /**
     * extending the existing database channel to "morph map" the type column
     * Note to my future self: MorphedDatabaseChannel is registered in NotificationServiceProvider
     */
    protected function buildPayload($notifiable, Notification $notification): array {
        return [
            'id' => $notification->id,
            'type' => NotificationService::userNotifications()[get_class($notification)] ?? get_class($notification),
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
        ];
    }
}
