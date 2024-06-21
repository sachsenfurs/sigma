<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('app', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('event_name', 'SIGMA');
            $blueprint->add('event_start', now()->setHours(0)->setMinutes(0)->setSeconds(0));
            $blueprint->add('event_end', now()->addDays(3)->setHours(0)->setMinutes(0)->setSeconds(0));
            $blueprint->add('show_schedule_date', now()->addDays(14)->setHours(16)->setMinutes(0)->setSeconds(0));

            $blueprint->add("lassie_api_key", "ae629a834729fd3aac6d1f827b1793b0");
            $blueprint->add("lassie_con_id", 10);
            $blueprint->add("lassie_con_event_id", null);
            $blueprint->add("lost_found_enabled", true);

            $blueprint->add("telegram_bot_name", env("TELEGRAM_BOT_TOKEN", ""));
            $blueprint->add("telegram_bot_token", env("TELEGRAM_BOT_NAME", ""));

            $blueprint->add("deepl_api_key", env("DEEPL_API_KEY"));
            $blueprint->add("deepl_source_lang", "de"); // https://developers.deepl.com/docs/v/de/resources/supported-languages#source-languages
            $blueprint->add("deepl_target_lang", "en-US");
        });
    }
};
