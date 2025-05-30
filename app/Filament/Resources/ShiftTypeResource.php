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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

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


    public static function canAccess(): bool {
        return auth()->user()->can("updateAny", ShiftType::class);
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Select::make('user_role_id')
                    ->relationship('userRole', 'name', fn(Builder $query) => $query->whereIn("id", auth()->user()->roles->pluck("id")))
                    ->label(__("Department"))
                    ->default(fn(Component $livewire) => data_get($livewire->tableFilters, "userRole.value", null))
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label(__("Name"))
                    ->autofocus()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->label(__("Description"))
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->label(__("Color"))
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('userRole.name')
                    ->label(__("Department"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__("Name"))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__("Description"))
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label(__("Color")),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('userRole')
                    ->label(__("Department"))
                    ->columnSpanFull()
                    ->relationship('userRole', 'name', fn(Builder $query) => $query->whereIn("id", auth()->user()->roles->pluck("id")))
                    ->modifyBaseQueryUsing(fn(Builder $query) => $query->whereIn("user_role_id", auth()->user()->roles->pluck("id")))
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
