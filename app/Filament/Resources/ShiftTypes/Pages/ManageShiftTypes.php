<?php

namespace App\Filament\Resources\ShiftTypes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ShiftTypes\ShiftTypeResource;
use Filament\Resources\Pages\ManageRecords;

class ManageShiftTypes extends ManageRecords
{
    protected static string $resource = ShiftTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
