<?php

namespace App\Filament\Resources\Ddas\ArtshowBidResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Ddas\ArtshowBidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtshowBids extends ListRecords
{
    protected static string $resource = ArtshowBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
