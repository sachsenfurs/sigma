<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('app', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('event_name', env("EVENT_NAME", 'SIGMA'));
            $blueprint->add('event_start', env("EVENT_START", now()->setHours(0)->setMinutes(0)->setSeconds(0)));
            $blueprint->add('event_end', env("EVENT_END", now()->addDays(3)->setHours(0)->setMinutes(0)->setSeconds(0)));
            $blueprint->add('show_schedule_date', env("SHOW_SCHEDULE_DATE", now()->subDay()->setHours(16)->setMinutes(0)->setSeconds(0)));

            $blueprint->add("lassie_api_key", env("LASSIE_API_KEY", "ae629a834729fd3aac6d1f827b1793b0"));
            $blueprint->add("lassie_con_id", env("LASSIE_CON_ID", 10));
            $blueprint->add("lassie_con_event_id", env("LASSIE_CON_EVENT_ID", null));
            $blueprint->add("lost_found_enabled", env("LOST_FOUND_ENABLED", true));

            $blueprint->add("telegram_bot_name", env("TELEGRAM_BOT_NAME", ""));
            $blueprint->add("telegram_bot_token", env("TELEGRAM_BOT_TOKEN", ""));

            $blueprint->add("deepl_api_key", env("DEEPL_API_KEY"));
            $blueprint->add("deepl_source_lang", env("DEEPL_SOURCE_LANG", "de")); // https://developers.deepl.com/docs/v/de/resources/supported-languages#source-languages
            $blueprint->add("deepl_target_lang", env("DEEPL_TARGET_LANG", "en-US"));
        });
    }
};
