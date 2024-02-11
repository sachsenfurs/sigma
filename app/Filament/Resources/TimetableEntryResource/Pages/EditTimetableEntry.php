<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;

use App\Filament\Resources\TimetableEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimetableEntry extends EditRecord
{
    protected static string $resource = TimetableEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return __('Manage Event Schedule');

    }

    protected function getFooterWidgets(): array
    {
        if ($this->record->sigEvent->reg_possible) {
            // Only show the TimeslotTable widget if the event allows registration
            return [
                TimetableEntryResource\Widgets\TimeslotTable::class,
            ];
        }
        return [];
    }

}
