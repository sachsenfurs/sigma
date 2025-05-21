<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PageHookSettings extends Settings
{
    public bool $show_in_source;
    public static function group(): string {
        return 'pagehooks';
    }
}
