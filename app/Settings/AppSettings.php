<?php

namespace App\Settings;

use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;

class AppSettings extends Settings
{

    public string $event_name;
    public Carbon $event_start;
    public Carbon $event_end;
    public Carbon $show_schedule_date;

    public string $lassie_api_key;
    public ?int $lassie_con_id;
    public ?int $lassie_con_event_id;
    public bool $lost_found_enabled;

    public ?string $deepl_api_key;
    public string $deepl_source_lang;
    public string $deepl_target_lang;

    public ?string $telegram_bot_token;
    public ?string $telegram_bot_name;

    public function hasEventStarted(): bool {
        return $this->event_start->isAfter(now());
    }

    public static function group(): string {
        return 'app';
    }

}
