<?php

namespace App\Filament\Resources\SigFormResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SigFormResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSigForms extends ListRecords
{
    protected static string $resource = SigFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
