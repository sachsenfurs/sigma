<?php

namespace App\Filament\Resources\Ddas\ArtshowArtists\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Ddas\ArtshowArtists\ArtshowArtistResource;
use Filament\Resources\Pages\EditRecord;

class EditArtshowArtist extends EditRecord
{
    protected static string $resource = ArtshowArtistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
