<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('app', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('sig_application_deadline', now()->addDays(7)->setHours(16)->setMinutes(0)->setSeconds(0));
            $blueprint->add('accept_sigs_after_deadline', true);
        });
    }
};
