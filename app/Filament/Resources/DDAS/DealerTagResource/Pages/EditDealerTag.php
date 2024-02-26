<?php

namespace App\Filament\Resources\DDAS\DealerTagResource\Pages;

use App\Filament\Resources\DDAS\DealerTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDealerTag extends EditRecord
{
    protected static string $resource = DealerTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
