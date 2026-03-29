<?php

namespace App\Filament\Resources\Ddas\ArtshowPickups\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Ddas\ArtshowPickups\ArtshowPickupResource;
use Filament\Resources\Pages\EditRecord;

class EditArtshowPickup extends EditRecord
{
    protected static string $resource = ArtshowPickupResource::class;

    protected function getHeaderActions(): array {
        return [
            DeleteAction::make(),
        ];
    }
}
