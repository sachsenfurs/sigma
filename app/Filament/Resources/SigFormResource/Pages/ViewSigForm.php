<?php

namespace App\Filament\Resources\SigFormResource\Pages;

use App\Filament\Resources\SigEventResource;
use App\Filament\Resources\SigFormResource;
use Filament\Actions;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSigForm extends ViewRecord
{
    protected static string $resource = SigFormResource::class;

    public function infolist(Infolist $infolist): Infolist {
        return $infolist->schema([
            RepeatableEntry::make('sigEvents')
                ->label("SIG")
                ->translateLabel()
                ->placeholder(__("None"))
                ->schema([
                    TextEntry::make("name_localized")
                        ->label("Name")
                        ->translateLabel()
                        ->inlineLabel()
                        ->url(fn($record) => SigEventResource::getUrl('view', ['record' => $record])),
                ])
        ])
        ->columns(4);
    }

    public function getHeading(): string|Htmlable {
        return $this->record->name_localized;
    }

    protected function getHeaderActions(): array {
        return [
            Actions\EditAction::make(),
        ];
    }
}
