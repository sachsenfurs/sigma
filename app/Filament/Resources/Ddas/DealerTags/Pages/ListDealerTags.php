<?php

namespace App\Filament\Resources\Ddas\DealerTags\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Ddas\DealerTags\DealerTagResource;
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
