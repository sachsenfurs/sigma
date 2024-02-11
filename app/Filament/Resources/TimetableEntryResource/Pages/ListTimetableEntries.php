<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;

use App\Filament\Resources\TimetableEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimetableEntries extends ListRecords
{
    protected static string $resource = TimetableEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
