<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SigManagement;
use App\Filament\Resources\SigHostResource\RelationManagers\SigEventsRelationManager;
use App\Filament\Resources\SigTagResource\Pages;
use App\Models\SigTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SigTagResource extends Resource
{
    protected static ?string $model = SigTag::class;
    protected static ?string $cluster = SigManagement::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 100;

    public static function getPluralLabel(): ?string {
        return __('Tags');
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
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
            SigEventsRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListSigTags::route('/'),
            'create' => Pages\CreateSigTag::route('/create'),
            'view' => Pages\ViewSigTag::route('/{record}'),
            'edit' => Pages\EditSigTag::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('description')
                ->label('Description')
                ->translateLabel()
                ->searchable()
                ->limit(50),
            Tables\Columns\TextColumn::make('description_en')
                ->label('Description (English)')
                ->translateLabel()
                ->searchable()
                ->limit(50),
        ];
    }

    private static function getNameField(): Forms\Components\Component {
        return Forms\Components\TextInput::make('name')
            ->label('Name')
            ->translateLabel()
            ->required()
            ->maxLength(255)
            ->columnSpanFull();
    }

    private static function getDescriptionField(): Forms\Components\Component {
        return Forms\Components\Textarea::make('description')
            ->label('Description')
            ->required()
            ->translateLabel()
            ->rows(4)
            ->columnSpanFull();
    }

    private static function getDescriptionENField(): Forms\Components\Component {
        return Forms\Components\Textarea::make('description_en')
            ->label('Description (English)')
            ->required()
            ->translateLabel()
            ->rows(4)
            ->columnSpanFull();
    }

    private static function getIconField(): Forms\Components\Component {
        return Forms\Components\TextInput::make("icon")
            ->nullable()
            ->helperText("Bootstrap Icon Class")
            ->columnSpanFull();
    }
}
