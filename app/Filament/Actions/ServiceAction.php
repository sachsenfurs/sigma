<?php

namespace App\Filament\Actions;

use App\Services\Translator;
use App\Settings\AppSettings;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
