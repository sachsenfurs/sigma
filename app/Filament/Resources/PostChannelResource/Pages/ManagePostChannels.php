<?php

namespace App\Filament\Resources\PostChannelResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\PostChannelResource;
use Filament\Actions;
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
