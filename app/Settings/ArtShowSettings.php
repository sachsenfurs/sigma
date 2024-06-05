<?php

namespace App\Settings;

use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;

class ArtShowSettings extends Settings
{
    public bool $enabled;
    public Carbon $item_deadline;
    public Carbon $show_items_date;

    public static function group(): string {
        return 'artshow';
    }
}
