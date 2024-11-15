<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void {
        $this->migrator->inGroup('artshow', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('require_checkin', env("ARTSHOW.REQUIRE_CHECKIN", true));
        });
    }
};
