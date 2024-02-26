<?php

namespace App\Filament\Resources\DDAS\ArtshowArtistResource\Pages;

use App\Filament\Resources\DDAS\ArtshowArtistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArtshowArtist extends EditRecord
{
    protected static string $resource = ArtshowArtistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
