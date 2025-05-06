<?php

namespace App\Filament\Resources\DepartmentInfoResource\Pages;

use App\Filament\Resources\DepartmentInfoResource;
use App\Filament\Resources\TimetableEntryResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ListDepartmentInfos extends ListRecords
{
    protected static string $resource = DepartmentInfoResource::class;

    public function infolist(Infolist $infolist): Infolist {
        return $infolist->schema([
            TextEntry::make("test"),
        ]);
    }

    public function getSubheading(): string|Htmlable|null {
        return  new HtmlString('<a href="'.TimetableEntryResource::getUrl().'">'.__("You can also filter the department requirements in the schedule view!").'</a>');
    }

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
