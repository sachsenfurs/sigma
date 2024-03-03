<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;


use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTimetableEntry extends EditRecord
{
    protected static string $resource = TimetableEntryResource::class;

    protected static ?string $cluster = SigPlanning::class;

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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // If the send_update flag is set, we will update the timestamps
        $record->timestamps = false;
        if ($data['send_update'] ?? false) {
            $record->timestamps = true;
        }
        unset($data['send_update']);

        // If the reset_update flag is set, we will reset the updated_at timestamp
        if ($data['reset_update'] ?? false) {
            $record->updated_at = $record->created_at;
        }
        unset($data['reset_update']);

        $record->update($data);

        return $record;
    }

}
