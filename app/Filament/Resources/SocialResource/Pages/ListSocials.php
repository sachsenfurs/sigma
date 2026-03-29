<?php

namespace App\Filament\Resources\SocialResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SocialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocials extends ListRecords
{
    protected static string $resource = SocialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
