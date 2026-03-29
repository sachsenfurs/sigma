<?php

namespace App\Filament\Resources\SigLocations\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SigLocations\SigLocationResource;
use Filament\Resources\Pages\ListRecords;

class ListSigLocations extends ListRecords
{
    protected static string $resource = SigLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
