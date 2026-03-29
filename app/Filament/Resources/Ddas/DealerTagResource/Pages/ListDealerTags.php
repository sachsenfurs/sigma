<?php

namespace App\Filament\Resources\Ddas\DealerTagResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Ddas\DealerTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDealerTags extends ListRecords
{
    protected static string $resource = DealerTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
