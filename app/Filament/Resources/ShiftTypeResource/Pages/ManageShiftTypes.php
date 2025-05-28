<?php

namespace App\Filament\Resources\ShiftTypeResource\Pages;

use App\Filament\Resources\ShiftTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageShiftTypes extends ManageRecords
{
    protected static string $resource = ShiftTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
