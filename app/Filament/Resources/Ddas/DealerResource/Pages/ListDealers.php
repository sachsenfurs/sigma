<?php

namespace App\Filament\Resources\Ddas\DealerResource\Pages;

use App\Filament\Resources\Ddas\DealerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDealers extends ListRecords
{
    protected static string $resource = DealerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
