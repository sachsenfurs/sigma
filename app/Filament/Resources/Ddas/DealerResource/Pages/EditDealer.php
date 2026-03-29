<?php

namespace App\Filament\Resources\Ddas\DealerResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Ddas\DealerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDealer extends EditRecord
{
    protected static string $resource = DealerResource::class;

    protected function getHeaderActions(): array {
        return [
            DeleteAction::make(),
        ];
    }
}
