<?php

namespace App\Filament\Resources\SigLocations\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SigLocations\SigLocationResource;
use Filament\Resources\Pages\EditRecord;

class EditSigLocation extends EditRecord
{
    protected static string $resource = SigLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
