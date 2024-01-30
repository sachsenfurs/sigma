<?php

namespace App\Filament\Resources\DDAS\ArtshowBidResource\Pages;

use App\Filament\Resources\DDAS\ArtshowBidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtshowBids extends ListRecords
{
    protected static string $resource = ArtshowBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
