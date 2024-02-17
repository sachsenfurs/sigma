<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;


use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;

class EditTimetableEntry extends EditRecord
{
    protected static string $resource = TimetableEntryResource::class;

    protected static ?string $cluster = SigPlanning::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormSchema(): array {

        return [
            TextInput::make("name"),
        ];
    }
}
