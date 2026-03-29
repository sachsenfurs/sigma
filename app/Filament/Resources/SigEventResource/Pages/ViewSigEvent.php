<?php

namespace App\Filament\Resources\SigEventResource\Pages;

use Filament\Schemas\Schema;
use App\Filament\Resources\SigEventResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSigEvent extends ViewRecord
{
    protected static string $resource = SigEventResource::class;

    protected function getHeaderActions(): array {
        return [
            EditAction::make(),
        ];
    }

    public function getHeading(): string|Htmlable {
        return $this->record->name_localized;
    }

    public function infolist(Schema $schema): Schema {
        return $infolist->schema(self::getInfolistSchema());
    }

    public static function getInfolistSchema() {
        return [
            TextEntry::make('description_localized')
                ->label("Description")
                ->translateLabel()
                ->markdown()
                ->columnSpanFull()
                ->placeholder(__("None"))
                ->listWithLineBreaks(),
            TextEntry::make("additional_info")
                ->label("Additional Information")
                ->translateLabel()
                ->markdown()
                ->columnSpanFull()
                ->placeholder(__("None"))
                ->listWithLineBreaks(),
        ];
    }
}
