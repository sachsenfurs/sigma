<?php

namespace App\Filament\Resources\PostChannelResource\Pages;

use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use App\Filament\Resources\PostChannelResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;

class ViewPostChannel extends ViewRecord
{
    protected static string $resource = PostChannelResource::class;

    public function infolist(Schema $schema): Schema {
        return $infolist->schema(self::getInfolistSchema());
    }

    public static function getInfolistSchema(): array {
        return [
            TextEntry::make('name')
                ->label("")
                ->size(TextSize::Large),
            TextEntry::make("info")
                ->label("")
                ->markdown()
                ->columnSpanFull()
                ->placeholder(__("None"))
                ->listWithLineBreaks(),
        ];
    }
}
