<?php

namespace App\Filament\Clusters\SigPlanning;

use App\Filament\Traits\HasActiveIcon;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class SigPlanningCluster extends Cluster
{
    use HasActiveIcon;
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedSquares2x2;
    protected static ?int $navigationSort = 1;
    protected static string | UnitEnum | null $navigationGroup = 'SIG';

    public static function getNavigationLabel(): string {
        return __("Event Schedule");
    }

    protected ?string $heading = "";
    /**
     * @return string|null
     */
    public static function getClusterBreadcrumb(): ?string {
        return __("Event Schedule");
    }

    public function getTitle(): string|Htmlable {
        return __("Event Schedule");
    }

}
