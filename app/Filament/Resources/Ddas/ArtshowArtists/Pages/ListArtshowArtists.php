<?php

namespace App\Filament\Resources\Ddas\ArtshowArtists\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Ddas\ArtshowArtists\ArtshowArtistResource;
use Filament\Resources\Pages\ListRecords;

class ListArtshowArtists extends ListRecords
{
    protected static string $resource = ArtshowArtistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
