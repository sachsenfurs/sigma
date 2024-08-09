<?php

namespace App\Filament\Resources\Ddas\ArtshowItemResource\Pages;

use App\Filament\Resources\Ddas\ArtshowItemResource;
use App\Models\Ddas\ArtshowItem;
use App\Services\ArtshowNotificationService;
use App\Settings\ArtShowSettings;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListArtshowItems extends ListRecords
{
    protected static string $resource = ArtshowItemResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\Action::make("notify winners")
                ->label("Notify Winners")
                ->translateLabel()
                ->disabled(app(ArtShowSettings::class)->bid_end_date->isAfter(now()))
                ->requiresConfirmation()
                ->action(function() {
                    ArtshowNotificationService::notifyWinners();
                    Notification::make("ok")
                        ->body(__("Winners have been notified!"))
                        ->success()
                        ->send();
                })
                ->authorize("deleteAny", ArtshowItem::class),
        ];
    }
}
