<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\SubNavigationPosition;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = "System";
    protected static ?int $navigationSort = 1999;

    public static function getNavigationLabel(): string {
        return __("Settings");
    }
}
