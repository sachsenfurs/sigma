<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('artshow', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('charity_min_percentage', 0);
            $blueprint->add('bid_start_date', now()->addDays(30)->setMinute(0)->setSecond(0));
            $blueprint->add('bid_end_date', now()->addDays(35)->setMinute(0)->setSecond(0));
            $blueprint->add('max_bids_per_item', 10);
        });
    }
};
