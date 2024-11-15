<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('artshow', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('enabled', env("ARTSHOW.ENABLED", true));
            $blueprint->add('item_deadline', env("ARTSHOW.ITEM_DEADLINE", now()->addDays(30)->setHour(0)->setMinute(0)->setSecond(0)));
            $blueprint->add('show_items_date', env("ARTSHOW.SHOW_ITEMS_DATE", now()->addDays(30)->setHour(0)->setMinute(0)->setSecond(0)));

        });
    }
};
