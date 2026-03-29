<?php

namespace App\Filament\Resources\SigTimeslots\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SigTimeslots\SigTimeslotResource;
use Filament\Resources\Pages\ListRecords;

class ListSigTimeslots extends ListRecords
{
    protected static string $resource = SigTimeslotResource::class;

    protected function getHeaderActions(): array {
        return [
            CreateAction::make(),
        ];
    }
}
