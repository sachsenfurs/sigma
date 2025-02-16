<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class NotificationsRelationManager extends RelationManager
{
    protected static string $relationship = 'notifications';
    protected static ?string $icon = "heroicon-o-bell";

    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Notifications");
    }

    public static function getModelLabel(): ?string {
        return __("Notification");
    }

    protected static function getPluralModelLabel(): ?string {
        return __("Notifications");
    }

    public function infolist(Infolist $infolist): Infolist {
        return $infolist->schema([
            TextEntry::make("type")
                ->columnSpanFull()
                ->formatStateUsing(fn($record) => method_exists($record->type, "getName") ? $record->type::getName() : $record->type),
            TextEntry::make("data")
                ->columnSpanFull()
                ->listWithLineBreaks()
                ->getStateUsing(fn($record) => new HtmlString("<pre>".(print_R($record->data, true))."</pre>")),
        ]);
    }

    public function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn($record) => method_exists($record->type, "getName") ? $record->type::getName() : $record->type),
                Tables\Columns\TextColumn::make("read_at")
                    ->label(__("Read at"))
                    ->placeholder(__("No"))
                    ->dateTime(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label(__("Created"))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
