<?php

namespace App\Filament\Resources\SigForms\Pages;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use App\Filament\Resources\SigEvents\SigEventResource;
use App\Filament\Resources\SigForms\SigFormResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSigForm extends ViewRecord
{
    protected static string $resource = SigFormResource::class;

    public function infolist(Schema $schema): Schema {
        return $schema->schema([
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
            EditAction::make(),
        ];
    }
}
