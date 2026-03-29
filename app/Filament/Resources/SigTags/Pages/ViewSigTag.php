<?php

namespace App\Filament\Resources\SigTags\Pages;

use Filament\Schemas\Schema;
use App\Filament\Resources\SigTags\SigTagResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSigTag extends ViewRecord
{
    protected static string $resource = SigTagResource::class;

    public function getHeading(): string|Htmlable {
        return $this->record->description_localized;
    }

    public function infolist(Schema $schema): Schema {
        return $schema->schema([
            TextEntry::make("name"),
        ]);
    }
}
