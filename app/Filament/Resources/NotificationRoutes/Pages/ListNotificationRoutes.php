<?php

namespace App\Filament\Resources\NotificationRoutes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\NotificationRoutes\NotificationRouteResource;
use Filament\Resources\Pages\ListRecords;

class ListNotificationRoutes extends ListRecords
{
    protected static string $resource = NotificationRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
