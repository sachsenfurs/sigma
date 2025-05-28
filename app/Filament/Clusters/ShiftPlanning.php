<?php

namespace App\Filament\Clusters;

use App\Filament\Traits\HasActiveIcon;
use Filament\Clusters\Cluster;

class ShiftPlanning extends Cluster
{
    use HasActiveIcon;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';
    protected static ?string $navigationGroup = "SIG";
    protected static ?int $navigationSort = 17;
    protected static ?string $clusterBreadcrumb = "";
    public static function getNavigationLabel(): string {
        return __("Shift Planning");
    }


}
