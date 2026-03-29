<?php

namespace App\Filament\Clusters\Settings;

use App\Filament\Traits\HasActiveIcon;
use BackedEnum;
use Filament\Clusters\Cluster;
use UnitEnum;

class SettingsCluster extends Cluster
{
    use HasActiveIcon;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string | UnitEnum | null $navigationGroup = "System";
    protected static ?int $navigationSort = 1999;

    public static function getNavigationLabel(): string {
        return __("Settings");
    }
}
