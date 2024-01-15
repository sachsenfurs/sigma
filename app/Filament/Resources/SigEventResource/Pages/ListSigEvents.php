<?php

namespace App\Filament\Resources\SigEventResource\Pages;

use App\Filament\Resources\SigEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSigEvents extends ListRecords
{
    protected static string $resource = SigEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
