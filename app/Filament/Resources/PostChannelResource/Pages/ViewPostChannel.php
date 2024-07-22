<?php

namespace App\Filament\Resources\PostChannelResource\Pages;

use App\Filament\Resources\PostChannelResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPostChannel extends ViewRecord
{
    protected static string $resource = PostChannelResource::class;

    public function infolist(Infolist $infolist): Infolist {
        return $infolist->schema(self::getInfolistSchema());
    }

    public static function getInfolistSchema(): array {
        return [
            TextEntry::make('name')
                ->label("")
                ->size(TextEntry\TextEntrySize::Large),
            TextEntry::make("info")
                ->label("")
                ->markdown()
                ->columnSpanFull()
                ->placeholder(__("None"))
                ->listWithLineBreaks(),
        ];
    }
}
