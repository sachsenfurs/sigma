<?php

namespace App\Filament\Resources\SigTagResource\Pages;

use App\Filament\Resources\SigTagResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSigTag extends ViewRecord
{
    protected static string $resource = SigTagResource::class;

    public function getHeading(): string|Htmlable {
        return $this->record->description_localized;
    }

    public function infolist(Infolist $infolist): Infolist {
        return $infolist->schema([
            TextEntry::make("name"),
            TextEntry::make("description")
                ->label("Description")
                ->translateLabel()
                ->placeholder(__("None")),
        ]);
    }
}
