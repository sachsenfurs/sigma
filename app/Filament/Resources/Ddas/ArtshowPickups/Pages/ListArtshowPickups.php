<?php

namespace App\Filament\Resources\Ddas\ArtshowPickups\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Ddas\ArtshowPickups\ArtshowPickupResource;
use Filament\Resources\Pages\ListRecords;

class ListArtshowPickups extends ListRecords
{
    protected static string $resource = ArtshowPickupResource::class;

    protected function getHeaderActions(): array {
        return [
            CreateAction::make(),
        ];
    }
}
