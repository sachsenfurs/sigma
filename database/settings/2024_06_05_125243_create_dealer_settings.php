<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('dealer', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('enabled', env("DEALER.ENABLED", true));
            $blueprint->add('signup_deadline', env("DEALER.SIGNUP_DEADLINE", now()->addDays(30)->setHour(0)->setMinute(0)->setSecond(0)));
            $blueprint->add('show_dealers_date', env("DEALER.SHOW_DEALERS_DATE", now()->addDays(30)->setHour(0)->setMinute(0)->setSecond(0)));
        });
    }
};
