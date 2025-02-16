<?php

namespace App\Models;

use App\Facades\NotificationService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationRoute extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'channels' => 'array'
    ];

    /**
     * returns fully qualified class name of notification
     */
    public function notification(): Attribute {
        return Attribute::make(
            get: fn(string $name): ?string => NotificationService::resolveClass($name) ?? $name
        );
    }

    public function notificationName(): Attribute {
        return Attribute::make(
            get: fn(): ?string => $this->notification::getName()
        );
    }

    public function notifiable(): MorphTo {
        return $this->morphTo();
    }
}
