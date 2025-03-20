<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ChatSettings extends Settings
{
    public bool $enabled;

    public static function group(): string {
        return 'chat';
    }
}
