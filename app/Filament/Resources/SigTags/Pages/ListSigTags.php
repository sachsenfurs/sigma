<?php

namespace App\Filament\Resources\SigTags\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SigTags\SigTagResource;
use Filament\Resources\Pages\ListRecords;

class ListSigTags extends ListRecords
{
    protected static string $resource = SigTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
