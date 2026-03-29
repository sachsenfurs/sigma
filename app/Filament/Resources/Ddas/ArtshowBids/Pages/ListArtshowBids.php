<?php

namespace App\Filament\Resources\Ddas\ArtshowBids\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Ddas\ArtshowBids\ArtshowBidResource;
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
