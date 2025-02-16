<?php

namespace App\Filament\Actions;

use Filament\Notifications\Notification;

class ServiceAction
{
    public static function notAvailable(): void {
        Notification::make("not_available")
            ->title(__("Not Available"))
            ->body(__("This feature is currently not available. Please check your configuration"))
            ->warning()
            ->send();
    }
}
