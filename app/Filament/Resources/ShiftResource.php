<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ShiftPlanning;
use App\Filament\Traits\HasActiveIcon;
use App\Models\Shift;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShiftResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = Shift::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $cluster = ShiftPlanning::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_role_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('shift_type_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('sig_location_id')
                    ->relationship('sigLocation', 'name')
                    ->default(null),
                Forms\Components\TextInput::make('max_user')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('info')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\DateTimePicker::make('start')
                    ->required(),
                Forms\Components\DateTimePicker::make('end')
                    ->required(),
                Forms\Components\TextInput::make('necessity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('team')
                    ->required(),
                Forms\Components\Toggle::make('locked')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_role_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shift_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sigLocation.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_user')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('necessity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('team')
                    ->boolean(),
                Tables\Columns\IconColumn::make('locked')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ShiftResource\Pages\ManageShifts::route('/'),
        ];
    }
}
