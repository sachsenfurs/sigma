<?php

namespace App\Filament\Resources\SigHostResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SigHostResource;
use Filament\Actions;
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
