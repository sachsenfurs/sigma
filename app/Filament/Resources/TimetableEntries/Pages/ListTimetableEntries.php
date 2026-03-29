<?php

namespace App\Filament\Resources\TimetableEntries\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TimetableEntries\Widgets\UnprocessedSigEvents;
use App\Filament\Clusters\SigPlanning\SigPlanningCluster;
use App\Filament\Resources\TimetableEntries\TimetableEntryResource;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListTimetableEntries extends ListRecords
{
    protected static string $resource = TimetableEntryResource::class;
    protected static ?string $cluster = SigPlanningCluster::class;

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
