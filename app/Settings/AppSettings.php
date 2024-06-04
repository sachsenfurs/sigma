<?php

namespace App\Settings;

use Illuminate\Support\Carbon;
use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\DateTimeInterfaceCast;

class AppSettings extends Settings
{

    public string $event_name;

    public \Carbon\Carbon $event_start;

    public static function casts(): array {
        return [
            'event_start' => DateTimeInterfaceCast::class,
        ];
    }

    public static function group(): string {
        return 'app';
    }
}
