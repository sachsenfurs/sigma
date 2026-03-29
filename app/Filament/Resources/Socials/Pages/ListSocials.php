<?php

namespace App\Filament\Resources\Socials\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Socials\SocialResource;
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
