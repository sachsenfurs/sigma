<?php

namespace App\Filament\Resources\DDAS\ArtshowPickupResource\Pages;

use App\Filament\Resources\DDAS\ArtshowPickupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArtshowPickup extends EditRecord
{
    protected static string $resource = ArtshowPickupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
