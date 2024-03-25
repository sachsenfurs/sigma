<?php

namespace App\Filament\Resources\DDAS\ArtshowPickupResource\Pages;

use App\Filament\Resources\DDAS\ArtshowPickupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtshowPickups extends ListRecords
{
    protected static string $resource = ArtshowPickupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
