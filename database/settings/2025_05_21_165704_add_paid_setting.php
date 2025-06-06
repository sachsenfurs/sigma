<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('artshow', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('paid_only', false);
        });

        $this->migrator->inGroup('dealer', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('paid_only', false);
        });

        $this->migrator->inGroup('app', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('paid_only', false);
        });
    }
};
