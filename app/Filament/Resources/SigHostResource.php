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

class SigHostResource extends Resource
{
    protected static ?string $model = SigHost::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'SIG';

    protected static ?string $label = 'SIG Hosts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reg_id')
                    ->label('Reg Number')
                    ->translateLabel()
                    ->formatStateUsing(function (SigHost $record = null) {
                        // Needs to be done, otherwise the form field is empty (Bug in filament?)
                        return $record->reg_id ?? null;
                    })
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->translateLabel()
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('hide')
                    ->label('Hide name on schedule')
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reg_id')
                    ->label('Reg Number')
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),
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
                Tables\Columns\IconColumn::make('hide')
                    ->label('Hidden')
                    ->translateLabel()
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('reg_id')
            ->defaultPaginationPageOption('25')
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
}
