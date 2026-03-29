<?php

namespace App\Filament\Resources\Ddas\ArtshowArtists\Pages;

use App\Filament\Resources\Ddas\ArtshowArtists\ArtshowArtistResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArtshowArtist extends CreateRecord
{
    protected static string $resource = ArtshowArtistResource::class;
}
