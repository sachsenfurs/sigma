<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ShiftPlanning;
use App\Filament\Resources\ShiftTypeResource\Pages;
use App\Filament\Traits\HasActiveIcon;
use App\Models\ShiftType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ShiftTypeResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $model = ShiftType::class;
    protected static ?string $cluster = ShiftPlanning::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationLabel(): string {
        return __("Shift Types");
    }

    public static function getModelLabel(): string {
        return __("Shift Type");
    }

    public static function getPluralModelLabel(): string {
        return __("Shift Types");
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\Select::make('user_role_id')
                    ->relationship('userRole', 'name')
                    ->required(),
                Forms\Components\ColorPicker::make('color')
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('userRole.name')
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('userRole')
                    ->label(__("Department"))
                    ->relationship('userRole', 'name')
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->name_localized),

            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
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

    public static function getPages(): array {
        return [
            'index' => Pages\ManageShiftTypes::route('/'),
        ];
    }
}
