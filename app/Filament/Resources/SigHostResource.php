<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SigHostResource\Pages;
use App\Filament\Resources\SigHostResource\RelationManagers;
use App\Models\SigHost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SigHostResource extends Resource
{
    protected static ?string $model = SigHost::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'SIG';
    protected static ?int $navigationSort = 20;

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->permissions()->contains('manage_sig_base_data');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Hosts');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::getNameField(),
                self::getRegIdField(),
                self::getDescriptionField(),
                self::getHideField(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
            ->defaultSort('reg_id')
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
            RelationManagers\SigEventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSigHosts::route('/'),
            'create' => Pages\CreateSigHost::route('/create'),
            'edit' => Pages\EditSigHost::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array
    {
        return [
            Tables\Columns\ViewColumn::make('name')
                ->label('Name')
                ->translateLabel()
                ->searchable()
                ->sortable()
                ->view('filament.tables.columns.host-avatar'),
            Tables\Columns\TextColumn::make('reg_id')
                ->label('Reg Number')
                ->translateLabel()
                ->numeric()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('description')
                ->label('Description')
                ->translateLabel()
                ->searchable()
                ->limit(50),
            Tables\Columns\TextColumn::make('publicSigEventCount')
                ->label('Event count')
                ->translateLabel()
                ->getStateUsing(fn (SigHost $record) => $record->getPublicSigEventCount()),
            Tables\Columns\IconColumn::make('hide')
                ->label('Hidden')
                ->translateLabel()
                ->boolean()
                ->sortable(),
        ];
    }

    private static function getNameField(): Forms\Components\Component
    {
        return
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->translateLabel()
                ->required()->maxLength(255);
    }

    private static function getRegIdField(): Forms\Components\Component
    {
        return
            Forms\Components\TextInput::make('reg_id')
                ->label('Reg Number')
                ->translateLabel()
                ->formatStateUsing(function (SigHost $record = null) {
                    // Needs to be done, otherwise the form field is empty (Bug in filament?)
                    return $record->reg_id ?? null;
                })
                ->numeric();
    }

    private static function getDescriptionField(): Forms\Components\Component
    {
        return
            Forms\Components\Textarea::make('description')
                ->label('Description (German)')
                ->translateLabel()
                ->required()
                ->maxLength(65535)
                ->columnSpanFull();
    }

    private static function getHideField(): Forms\Components\Component
    {
        return
            Forms\Components\Toggle::make('hide')
                ->label('Hide name on schedule')
                ->translateLabel();
    }
}
