<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('pagehooks', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('show_in_source', false);

        });
    }
};
