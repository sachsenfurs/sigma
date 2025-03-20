<?php

namespace App\Filament\Resources\SigHostResource\Pages;

use App\Filament\Resources\SigHostResource;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSigHost extends ViewRecord
{
    protected static string $resource = SigHostResource::class;

    public function getHeading(): string|Htmlable {
        return $this->record->name;
    }

    public function infolist(Infolist $infolist): Infolist {
        return $infolist->schema([
            TextEntry::make('reg_id')
                ->label("Reg Number")
                ->translateLabel()
                ->placeholder(__("None")),
            TextEntry::make('description')
                ->placeholder(__("None")),
            IconEntry::make('hide')
                ->label("Hide name on schedule")
                ->translateLabel()
                ->hidden(fn($state) => !$state),
        ]);
    }
}
