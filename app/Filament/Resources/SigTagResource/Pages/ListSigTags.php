<?php

namespace App\Filament\Resources\SigTagResource\Pages;

use App\Filament\Resources\SigTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSigTags extends ListRecords
{
    protected static string $resource = SigTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
