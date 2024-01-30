<?php

namespace App\Filament\Resources\DDAS\DealerTagResource\Pages;

use App\Filament\Resources\DDAS\DealerTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDealerTags extends ListRecords
{
    protected static string $resource = DealerTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
