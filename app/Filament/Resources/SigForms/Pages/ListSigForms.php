<?php

namespace App\Filament\Resources\SigForms\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SigForms\SigFormResource;
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
