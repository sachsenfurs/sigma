<?php

namespace App\Filament\Resources;

use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SigTagResource\Pages\ListSigTags;
use App\Filament\Resources\SigTagResource\Pages\CreateSigTag;
use App\Filament\Resources\SigTagResource\Pages\ViewSigTag;
use App\Filament\Resources\SigTagResource\Pages\EditSigTag;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Filament\Clusters\SigManagement;
use App\Filament\Resources\SigHostResource\RelationManagers\SigEventsRelationManager;
use App\Filament\Resources\SigTagResource\Pages;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigTag;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SigTagResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = SigTag::class;
    protected static ?string $cluster = SigManagement::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';
    protected static ?\Filament\Pages\Enums\SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 100;

    public static function getPluralLabel(): ?string {
        return __('Tags');
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                self::getNameField(),
                self::getDescriptionField(),
                self::getDescriptionENField(),
                self::getIconField(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns(self::getTableColumns())
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
            SigEventsRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListSigTags::route('/'),
            'create' => CreateSigTag::route('/create'),
            'view' => ViewSigTag::route('/{record}'),
            'edit' => EditSigTag::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array {
        return [
            TextColumn::make('name')
                ->label('Name')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            TextColumn::make('description')
                ->label('Description')
                ->translateLabel()
                ->searchable()
                ->limit(50),
            TextColumn::make('description_en')
                ->label('Description (English)')
                ->translateLabel()
                ->searchable()
                ->limit(50),
        ];
    }

    private static function getNameField(): Component {
        return TextInput::make('name')
            ->label('Name')
            ->translateLabel()
            ->required()
            ->maxLength(255)
            ->columnSpanFull();
    }

    private static function getDescriptionField(): Component {
        return Textarea::make('description')
            ->label('Description')
            ->required()
            ->translateLabel()
            ->rows(4)
            ->columnSpanFull();
    }

    private static function getDescriptionENField(): Component {
        return Textarea::make('description_en')
            ->label('Description (English)')
            ->required()
            ->translateLabel()
            ->rows(4)
            ->columnSpanFull();
    }

    private static function getIconField(): Component {
        return TextInput::make("icon")
            ->nullable()
            ->helperText("Bootstrap Icon Class")
            ->columnSpanFull();
    }
}
