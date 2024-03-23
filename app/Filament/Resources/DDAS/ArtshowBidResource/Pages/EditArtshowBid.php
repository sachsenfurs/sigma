<?php

namespace App\Filament\Resources\DDAS\ArtshowBidResource\Pages;

use App\Filament\Resources\DDAS\ArtshowBidResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArtshowBid extends EditRecord
{
    protected static string $resource = ArtshowBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
