<?php

namespace App\Filament\Resources\SigFormsResource\Pages;

use App\Filament\Resources\SigFormsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSigForms extends ListRecords
{
    protected static string $resource = SigFormsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
