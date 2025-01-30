<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    public function type(): Attribute {
        return Attribute::make(
            get: fn($type) => array_flip(NotificationService::userNotifications())[$type] ?? $type
        );
    }

    public function view() {
        if(is_subclass_of($this->type, \App\Notifications\Notification::class)) {
            return $this->type::view([...$this->data, 'notification' => $this]);
        }

        return \App\Notifications\Notification::view([...$this->data, 'notification' => $this]);
    }
}
