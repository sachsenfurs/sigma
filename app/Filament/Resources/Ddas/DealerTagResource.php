<?php

namespace App\Filament\Resources\Ddas;

use App\Filament\Resources\Ddas\DealerTagResource\Pages;
use App\Filament\Resources\Ddas\DealerTagResource\RelationManagers\DealerTagRelationManager;
use App\Filament\Resources\DealerResource\RelationManagers\DealerTagResourceRelationManager;
use App\Models\Ddas\DealerTag;
use App\Settings\DealerSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DealerTagResource extends Resource
{
    protected static ?string $model = DealerTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Dealer\'s Den';

    protected static ?int $navigationSort = 320;

    public static function can(string $action, ?Model $record = null): bool {
        return auth()->user()->permissions()->contains('manage_dealers_den');
    }

    public static function canAccess(): bool {
        return parent::canAccess() AND app(DealerSettings::class)->enabled;
    }

    public static function getPluralLabel(): ?string {
        return __('Dealer Tags');
    }

    public static function getSchema() {
        return [
            Forms\Components\TextInput::make('name')
                  ->label('Tag Name')
                  ->required()
                  ->maxLength(255),
            Forms\Components\TextInput::make('name_en')
                  ->label('Tag Name (EN)')
                  ->required()
                  ->maxLength(255),
        ];
    }
    public static function form(Form $form): Form {
        return $form
            ->schema(
                self::getSchema()
            );
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            DealerTagRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListDealerTags::route('/'),
            'create' => Pages\CreateDealerTag::route('/create'),
            'edit' => Pages\EditDealerTag::route('/{record}/edit'),
        ];
    }
}
