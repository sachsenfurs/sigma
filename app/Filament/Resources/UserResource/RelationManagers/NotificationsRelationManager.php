<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class NotificationsRelationManager extends RelationManager
{
    protected static string $relationship = 'notifications';
    protected static string | \BackedEnum | null $icon = "heroicon-o-bell";

    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Notifications");
    }

    public static function getModelLabel(): ?string {
        return __("Notification");
    }

    protected static function getPluralModelLabel(): ?string {
        return __("Notifications");
    }

    public function infolist(Schema $schema): Schema {
        return $schema->components([
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
                TextColumn::make('type')
                    ->formatStateUsing(fn($record) => method_exists($record->type, "getName") ? $record->type::getName() : $record->type),
                TextColumn::make("read_at")
                    ->label(__("Read at"))
                    ->placeholder(__("No"))
                    ->dateTime(),
                TextColumn::make("created_at")
                    ->label(__("Created"))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
