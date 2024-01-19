<?php

namespace App\Filament\Resources\SigLocationResource\Pages;

use App\Filament\Resources\SigLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSigLocations extends ListRecords
{
    protected static string $resource = SigLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
