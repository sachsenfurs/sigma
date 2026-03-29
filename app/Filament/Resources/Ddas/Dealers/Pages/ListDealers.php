<?php

namespace App\Filament\Resources\Ddas\Dealers\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Ddas\Dealers\DealerResource;
use Filament\Resources\Pages\ListRecords;

class ListDealers extends ListRecords
{
    protected static string $resource = DealerResource::class;

    protected function getHeaderActions(): array {
        return [
            CreateAction::make(),
        ];
    }

}
