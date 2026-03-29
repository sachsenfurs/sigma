<?php

namespace App\Filament\Resources\Ddas\DealerTags\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Ddas\DealerTags\DealerTagResource;
use Filament\Resources\Pages\EditRecord;

class EditDealerTag extends EditRecord
{
    protected static string $resource = DealerTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
