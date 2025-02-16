<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class NotificationService extends Facade
{
    protected static function getFacadeAccessor(): string {
        return \App\Services\NotificationService::class;
    }
}
