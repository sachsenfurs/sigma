<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;

use App\Filament\Resources\TimetableEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTimetableEntry extends CreateRecord
{
    protected static string $resource = TimetableEntryResource::class;

    public function getHeading(): string
    {
        return __('Add Time Slot');
    }
}
