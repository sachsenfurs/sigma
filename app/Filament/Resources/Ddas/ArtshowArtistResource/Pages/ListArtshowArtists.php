<?php

namespace App\Filament\Resources\Ddas\ArtshowArtistResource\Pages;

use App\Filament\Resources\Ddas\ArtshowArtistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtshowArtists extends ListRecords
{
    protected static string $resource = ArtshowArtistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
