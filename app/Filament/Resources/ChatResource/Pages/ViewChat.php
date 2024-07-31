<?php

namespace App\Filament\Resources\ChatResource\Pages;

use App\Filament\Resources\ChatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChat extends ViewRecord
{
    protected static string $resource = ChatResource::class;

    protected static string $view = 'filament.resources.chats.pages.view-chat';

    /*
    protected function getFooterWidgets(): array
    {
        return [
            ChatResource\Widgets\ChatBox::class
        ];
    }
        */
}
