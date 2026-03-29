<?php

namespace App\Filament\Resources\SigEventResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Actions\EditAction;
use Filament\Actions\DetachAction;
use App\Filament\Resources\SigFormResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SigFormsRelationManager extends RelationManager
{
    protected static string $relationship = 'forms';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->forms()->count() ?: null;
    }

    public function form(Schema $schema): Schema {
        return $schema;
    }

    protected static ?string $modelLabel = "Form";
    protected static ?string $pluralModelLabel = "Forms";

    public function isReadOnly(): bool {
        return false;
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name_localized'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['name', 'name_en']),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn($record) => SigFormResource::getUrl('edit', ['record' => $record])),
                DetachAction::make()
                    ->recordTitleAttribute("name_localized"),
            ])
            ->recordUrl(null);
    }
}
