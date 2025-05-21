<?php

namespace App\Settings;

use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;

class ArtShowSettings extends Settings
{
    public bool $enabled;
    public Carbon $item_deadline;
    public Carbon $show_items_date;
    public int $charity_min_percentage;
    public Carbon $bid_start_date;
    public Carbon $bid_end_date;
    public int $max_bids_per_item;
    public bool $require_checkin;
    public bool $paid_only;

    public static function group(): string {
        return 'artshow';
    }
}
