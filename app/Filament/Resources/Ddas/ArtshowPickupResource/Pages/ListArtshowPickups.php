<?php

namespace App\Filament\Resources\Ddas\ArtshowPickupResource\Pages;

use App\Filament\Resources\Ddas\ArtshowPickupResource;
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
