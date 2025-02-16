<?php

namespace App\Models\Traits;

use App\Models\NotificationRoute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasNotificationRoutes
{
    public function notificationRoutes(): MorphMany {
        return $this->morphMany(NotificationRoute::class, 'notifiable');
    }
}
