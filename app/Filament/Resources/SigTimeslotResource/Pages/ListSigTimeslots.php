<?php

namespace App\Filament\Resources\SigTimeslotResource\Pages;

use App\Filament\Resources\SigTimeslotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSigTimeslots extends ListRecords
{
    protected static string $resource = SigTimeslotResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
