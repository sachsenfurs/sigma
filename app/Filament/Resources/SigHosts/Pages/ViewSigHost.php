<?php

namespace App\Filament\Resources\SigHosts\Pages;

use Filament\Schemas\Schema;
use App\Filament\Resources\SigHosts\SigHostResource;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSigHost extends ViewRecord
{
    protected static string $resource = SigHostResource::class;

    public function getHeading(): string|Htmlable {
        return $this->record->name;
    }

    public function infolist(Schema $schema): Schema {
        return $schema->schema([
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
