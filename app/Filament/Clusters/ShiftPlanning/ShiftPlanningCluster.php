<?php

namespace App\Filament\Clusters\ShiftPlanning;

use App\Filament\Traits\HasActiveIcon;
use BackedEnum;
use Filament\Clusters\Cluster;
use UnitEnum;

class ShiftPlanningCluster extends Cluster
{
    use HasActiveIcon;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-date-range';
    protected static string | UnitEnum | null $navigationGroup = "SIG";
    protected static ?int $navigationSort = 17;
    protected static ?string $clusterBreadcrumb = "";
    public static function getNavigationLabel(): string {
        return __("Shift Planning");
    }


}
