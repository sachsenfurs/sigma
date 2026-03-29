<?php

namespace App\Filament\Resources\ShiftTypes;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ShiftTypes\Pages\ManageShiftTypes;
use App\Filament\Clusters\ShiftPlanning\ShiftPlanningCluster;
use App\Filament\Traits\HasActiveIcon;
use App\Models\ShiftType;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ShiftTypeResource extends Resource
{
    use HasActiveIcon;
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCalendar;
    protected static ?string $model = ShiftType::class;
    protected static ?string $cluster = ShiftPlanningCluster::class;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

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

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Select::make('user_role_id')
                    ->relationship('userRole', 'name', fn(Builder $query) => $query->whereIn("id", auth()->user()->roles->pluck("id")))
                    ->label(__("Department"))
                    ->default(fn(Component $livewire) => data_get($livewire->tableFilters, "userRole.value", null))
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('name')
                    ->label(__("Name"))
                    ->autofocus()
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->label(__("Description"))
                    ->maxLength(255),
                ColorPicker::make('color')
                    ->label(__("Color"))
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('userRole.name')
                    ->label(__("Department"))
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__("Name"))
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__("Description"))
                    ->searchable(),
                ColorColumn::make('color')
                    ->label(__("Color")),
            ])
            ->filters([
                SelectFilter::make('userRole')
                    ->label(__("Department"))
                    ->columnSpanFull()
                    ->relationship('userRole', 'name', fn(Builder $query) => $query->whereIn("id", auth()->user()->roles->pluck("id")))
                    ->modifyBaseQueryUsing(fn(Builder $query) => $query->whereIn("user_role_id", auth()->user()->roles->pluck("id")))
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->name_localized),

            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => ManageShiftTypes::route('/'),
        ];
    }
}
