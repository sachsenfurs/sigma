<?php

namespace App\Filament\Resources\Ddas\DealerTags;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Ddas\DealerTags\Pages\ListDealerTags;
use App\Filament\Resources\Ddas\DealerTags\Pages\CreateDealerTag;
use App\Filament\Resources\Ddas\DealerTags\Pages\EditDealerTag;
use App\Filament\Resources\Ddas\DealerTags\RelationManagers\DealerTagRelationManager;
use App\Models\Ddas\DealerTag;
use App\Settings\DealerSettings;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DealerTagResource extends Resource
{
    protected static ?string $model = DealerTag::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedTag;

    protected static string | UnitEnum | null $navigationGroup = 'Dealer\'s Den';

    protected static ?int $navigationSort = 320;

    public static function getPluralLabel(): ?string {
        return __('Tags');
    }

    public static function getLabel(): ?string {
        return __("Tag");
    }

    public static function canAccess(): bool {
        return parent::canAccess() AND app(DealerSettings::class)->enabled;
    }


    public static function getSchema() {
        return [
            TextInput::make('name')
                  ->label('Tag Name')
                  ->translateLabel()
                  ->required()
                  ->maxLength(255),
            TextInput::make('name_en')
                  ->label('Tag Name (English)')
                  ->translateLabel()
                  ->required()
                  ->maxLength(255),
        ];
    }
    public static function form(Schema $schema): Schema {
        return $schema
            ->components(
                self::getSchema()
            );
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name_en')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListDealerTags::route('/'),
            'create' => CreateDealerTag::route('/create'),
            'edit' => EditDealerTag::route('/{record}/edit'),
        ];
    }
}
