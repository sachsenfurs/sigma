<?php

namespace App\Filament\Resources\SigEventResource\RelationManagers;

use App\Filament\Resources\SigFormResource;
use Filament\Forms\Form;
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

    public function form(Form $form): Form {
        return $form;
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
                Tables\Columns\TextColumn::make('name_localized'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['name', 'name_en']),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => SigFormResource::getUrl('edit', ['record' => $record])),
                Tables\Actions\DetachAction::make()
                    ->recordTitleAttribute("name_localized"),
            ])
            ->recordUrl(null);
    }
}
