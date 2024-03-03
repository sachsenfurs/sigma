<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;

use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListTimetableEntries extends ListRecords
{
    protected static string $resource = TimetableEntryResource::class;
    protected static ?string $cluster = SigPlanning::class;

//    protected ?string $heading = "";

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
