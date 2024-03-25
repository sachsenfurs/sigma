<?php

namespace App\Filament\Resources\SigEventResource\Pages;

use App\Filament\Resources\SigEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSigEvent extends EditRecord
{
    protected static string $resource = SigEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->modalHeading(__('Delete SIG')),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            SigEventResource\Widgets\TimetableEntriesTable::class,
        ];
    }
}
