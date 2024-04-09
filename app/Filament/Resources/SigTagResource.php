<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SigTagResource\Pages;
use App\Filament\Resources\SigTagResource\RelationManagers;
use App\Models\SigTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SigTagResource extends Resource
{
    protected static ?string $model = SigTag::class;

    protected static ?string $navigationGroup = 'SIG';
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 50;

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->can('manage_sig_base_data');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Tags');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::getNameField(),
                self::getDescriptionField(),
                self::getDescriptionENField(),
            ]);
    }

    public static function table(Table $table): Table
    {
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\SigTagsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSigTags::route('/'),
            'create' => Pages\CreateSigTag::route('/create'),
            'edit' => Pages\EditSigTag::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('description')
                ->label('German')
                ->translateLabel()
                ->searchable()
                ->limit(50),
            Tables\Columns\TextColumn::make('description_en')
                ->label('English')
                ->translateLabel()
                ->searchable()
                ->limit(50),
        ];
    }

    private static function getNameField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('name')
            ->label('Name')
            ->translateLabel()
            ->required()
            ->maxLength(255)
            ->columnSpanFull();
    }

    private static function getDescriptionField(): Forms\Components\Component
    {
        return Forms\Components\Textarea::make('description')
            ->label('German')
            ->translateLabel()
            ->rows(4)
            ->columnSpanFull();
    }

    private static function getDescriptionENField(): Forms\Components\Component
    {
        return Forms\Components\Textarea::make('description_en')
            ->label('English')
            ->translateLabel()
            ->rows(4)
            ->columnSpanFull();
    }
}
