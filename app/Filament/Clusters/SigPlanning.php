<?php

namespace App\Filament\Clusters;

use App\Filament\Traits\HasActiveIcon;
use Filament\Clusters\Cluster;
use Illuminate\Contracts\Support\Htmlable;

class SigPlanning extends Cluster
{
    use HasActiveIcon;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'SIG';

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
