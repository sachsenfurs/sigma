<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TimetableEntryResource\Widgets\UnprocessedSigEvents;
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
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array {
        return [
            UnprocessedSigEvents::class,
        ];
    }

    #[On('refresh')]
    public function refresh(): void {
        $this->loadTable();
    }
}
