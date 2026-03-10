<?php

namespace App\Settings;

use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;

class DealerSettings extends Settings
{
    public bool $enabled;
    public Carbon $signup_deadline;
    public Carbon $show_dealers_date;
    public bool $paid_only;
    public bool $image_mandatory;

    public static function group(): string {
        return 'dealer';
    }
}
