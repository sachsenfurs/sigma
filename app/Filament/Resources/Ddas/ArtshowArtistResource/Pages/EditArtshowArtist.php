<?php

namespace App\Filament\Resources\Ddas\ArtshowArtistResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Ddas\ArtshowArtistResource;
use Filament\Actions;
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
