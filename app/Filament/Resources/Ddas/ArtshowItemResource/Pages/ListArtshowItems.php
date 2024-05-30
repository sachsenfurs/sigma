<?php

namespace App\Filament\Resources\Ddas\ArtshowItemResource\Pages;

use App\Filament\Resources\Ddas\ArtshowItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtshowItems extends ListRecords
{
    protected static string $resource = ArtshowItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
