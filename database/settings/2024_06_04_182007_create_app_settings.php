<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('app', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('event_name', 'SIGMA');
            $blueprint->add('event_start', (new DateTime()));
            $blueprint->add('event_end', now()->addDays(3));

            $blueprint->add("lassie_api_key", "ae629a834729fd3aac6d1f827b1793b0");
            $blueprint->add("lassie_con_id", 10);
            $blueprint->add("lassie_con_event_id", null);
            $blueprint->add("lost_found_enabled", true);

            $blueprint->add("telegram_bot_name", env("TELEGRAM_BOT_TOKEN", ""));
            $blueprint->add("telegram_bot_token", env("TELEGRAM_BOT_NAME", ""));


            $blueprint->add("deepl_api_key", "");
            $blueprint->add("deepl_source_lang", "de-DE");
            $blueprint->add("deepl_target_lang", "en-US");


//            $blueprint->add('event_end', now()->addDays(3));

        });
    }
};
