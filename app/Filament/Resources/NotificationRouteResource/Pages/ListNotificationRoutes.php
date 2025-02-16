<?php

namespace App\Filament\Resources\NotificationRouteResource\Pages;

use App\Filament\Resources\NotificationRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotificationRoutes extends ListRecords
{
    protected static string $resource = NotificationRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
