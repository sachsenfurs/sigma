<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\SubNavigationPosition;

class SigPlanning extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 100;
    protected static ?string $navigationGroup = 'SIG';


    public static function getLabel(): ?string
    {
        return __('Event Schedule');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Event Schedule');
    }

}
