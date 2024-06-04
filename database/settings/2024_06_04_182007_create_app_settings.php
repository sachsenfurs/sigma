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

//            $blueprint->add('event_end', now()->addDays(3));

        });
    }
};
