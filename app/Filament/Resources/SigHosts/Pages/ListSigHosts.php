<?php

namespace App\Filament\Resources\SigHosts\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SigHosts\SigHostResource;
use Filament\Resources\Pages\ListRecords;

class ListSigHosts extends ListRecords
{
    protected static string $resource = SigHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
