<?php

namespace App\Filament\Resources\Chats\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Chats\ChatResource;
use Filament\Resources\Pages\ListRecords;

class ListChats extends ListRecords
{
    protected static string $resource = ChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->successRedirectUrl(fn($record) => ChatResource::getUrl('edit', compact("record"))),
        ];
    }
}
