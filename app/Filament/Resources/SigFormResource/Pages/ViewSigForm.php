<?php

namespace App\Filament\Resources\SigFormResource\Pages;

use App\Filament\Resources\SigFormResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSigForm extends ViewRecord
{
    protected static string $resource = SigFormResource::class;

    public function infolist(Infolist $infolist): Infolist {
        return $infolist->schema([
            TextEntry::make('sigEvent.name')
                ->label("SIG")
                ->translateLabel()
                ->placeholder(__("None")),
        ]);
    }

    public function getHeading(): string|Htmlable {
        return $this->record->name;
    }

    protected function getHeaderActions(): array {
        return [
            Actions\EditAction::make(),
        ];
    }
}
