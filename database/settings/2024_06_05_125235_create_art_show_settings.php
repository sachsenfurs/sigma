<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('artshow', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('enabled', true);
            $blueprint->add('item_deadline', now()->addDays(30)->setHour(0)->setMinute(0)->setSecond(0));
            $blueprint->add('show_items_date', now()->addDays(30)->setHour(0)->setMinute(0)->setSecond(0));

        });
    }
};
