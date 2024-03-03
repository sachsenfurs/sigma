<?php

namespace App\Filament\Resources\DDAS\DealerResource\Pages;

use App\Filament\Resources\DDAS\DealerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDealer extends EditRecord
{
    protected static string $resource = DealerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
