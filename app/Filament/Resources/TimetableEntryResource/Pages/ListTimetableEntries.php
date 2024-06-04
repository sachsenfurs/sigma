<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;

use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListTimetableEntries extends ListRecords
{
    protected static string $resource = TimetableEntryResource::class;
    protected static ?string $cluster = SigPlanning::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array {
        return [
            TimetableEntryResource\Widgets\UnprocessedSigEvents::class,
        ];
    }

    #[On('refresh')]
    public function refresh() {
        $this->loadTable();
    }
}
