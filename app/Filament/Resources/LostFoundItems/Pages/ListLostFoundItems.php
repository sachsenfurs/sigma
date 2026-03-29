<?php

namespace App\Filament\Resources\LostFoundItems\Pages;

use App\Filament\Resources\LostFoundItems\LostFoundItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLostFoundItems extends ListRecords
{
    protected static string $resource = LostFoundItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
