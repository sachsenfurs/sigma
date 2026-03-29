<?php

namespace App\Filament\Resources\Shifts;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Grouping\Group;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Toggle;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Clusters\ShiftPlanning\ShiftPlanningCluster;
use App\Filament\Resources\Shifts\Pages\ManageShifts;
use App\Filament\Resources\Shifts\Widgets\ShiftPlannerWidget;
use App\Filament\Resources\Shifts\Widgets\ShiftSummaryWidget;
use App\Filament\Traits\HasActiveIcon;
use App\Models\Shift;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ShiftResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = Shift::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $cluster = ShiftPlanningCluster::class;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected array $listeners = [
        'refreshShifts' => '$refresh',
    ];

    public static function getLabel(): ?string {
        return __("Shifts");
    }
    public static function getPluralLabel(): ?string {
        return __("Shifts");
    }

    public static function form(Schema $schema): Schema {
        return $schema->components((new ShiftPlannerWidget())->getSchema(Shift::class));
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with(["type", "type.userRole"]))
            ->columns([
                TextColumn::make('type.userRole')
                    ->formatStateUsing(fn($record) => $record->type->userRole?->name_localized)
                    ->label(__("Department"))
                    ->badge(),
                TextColumn::make('type.name')
                    ->label(__("Shift Type"))
                    ->sortable(),
                TextColumn::make('sigLocation.name')
                    ->label(__("Location"))
                    ->sortable(),
                TextColumn::make('userShifts.user.name')
                    ->label(__("User"))
                    ->searchable()
                    ->sortable()
                    ->badge(),
                TextColumn::make('info')
                    ->label(__("Additional Information"))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('start')
                    ->label(__("Duration"))
                    ->formatStateUsing(fn($record) =>
                        $record->start->translatedFormat("l, H:i")
                            . "-" . $record->end->translatedFormat("H:i")
                            . " (" . round($record->start->diffInHours($record->end), 2) . "h)"
                    )
                    ->sortable(),
                TextColumn::make('necessity')
                    ->label(__("Necessity"))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('max_user')
                    ->numeric()
                    ->label(__("Max. User"))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('team')
                    ->label("For all team member")
                    ->getStateUsing(fn($record) => $record->team ?: null)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                IconColumn::make('locked')
                    ->label(__("Locked"))
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__("Created"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__("Updated at"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Group::make("shift_type_id")
                    ->label(__("Shift Type"))
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn($record) => $record->type->name)
                    ->titlePrefixedWithLabel(false),
//                Tables\Grouping\Group::make("userShifts.user_id")
//                    ->label(__("User"))
//                    ->collapsible()
//                    ->getTitleFromRecordUsing(fn($record) => $record->)
//                    ->titlePrefixedWithLabel(false),

                ])
            ->filters([
                //
            ])
            ->defaultSort("start", "asc")
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkAction::make("locked")
                    ->authorize("deleteAny")
                    ->label(__("Locked")."...")
                    ->deselectRecordsAfterCompletion()
                    ->schema([
                        Toggle::make("locked")
                            ->label(__("Locked")),
                    ])
                    ->action(fn($data, $records) => $records->toQuery()->update($data)),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => ManageShifts::route('/'),
        ];
    }

    public static function getWidgets(): array {
        return [
            ShiftSummaryWidget::class,
        ];
    }
}
