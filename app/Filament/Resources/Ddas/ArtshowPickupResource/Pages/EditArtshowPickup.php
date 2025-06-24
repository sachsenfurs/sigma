<?php

namespace App\Filament\Resources\Ddas\ArtshowPickupResource\Pages;

use App\Filament\Resources\Ddas\ArtshowPickupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArtshowPickup extends EditRecord
{
    protected static string $resource = ArtshowPickupResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
