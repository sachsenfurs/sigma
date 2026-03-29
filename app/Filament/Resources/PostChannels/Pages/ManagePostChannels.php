<?php

namespace App\Filament\Resources\PostChannels\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\PostChannels\PostChannelResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePostChannels extends ManageRecords
{
    protected static string $resource = PostChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
