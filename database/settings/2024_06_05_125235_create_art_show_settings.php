<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('artshow', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('enabled', config("app.artshow.enabled"));
            $blueprint->add('item_deadline', config("app.artshow.item_deadline"));
            $blueprint->add('show_items_date', config("app.artshow.show_items_date"));
        });
    }
};
