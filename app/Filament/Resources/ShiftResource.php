<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ShiftPlanning;
use App\Filament\Resources\ShiftResource\Pages\ManageShifts;
use App\Filament\Resources\ShiftResource\Widgets\ShiftPlannerWidget;
use App\Filament\Resources\ShiftResource\Widgets\ShiftSummaryWidget;
use App\Filament\Traits\HasActiveIcon;
use App\Models\Shift;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ShiftResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = Shift::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $cluster = ShiftPlanning::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected array $listeners = [
        'refreshShifts' => '$refresh',
    ];

    public static function getLabel(): ?string {
        return __("Shifts");
    }
    public static function getPluralLabel(): ?string {
        return __("Shifts");
    }

    public static function form(Form $form): Form {
        return $form->schema((new ShiftPlannerWidget())->getSchema(Shift::class));
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with(["type", "type.userRole"]))
            ->columns([
                Tables\Columns\TextColumn::make('type.userRole')
                    ->formatStateUsing(fn($record) => $record->type->userRole?->name_localized)
                    ->label(__("Department"))
                    ->badge(),
                Tables\Columns\TextColumn::make('type.name')
                    ->label(__("Shift Type"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('sigLocation.name')
                    ->label(__("Location"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('userShifts.user.name')
                    ->label(__("User"))
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('info')
                    ->label(__("Additional Information"))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start')
                    ->label(__("Duration"))
                    ->formatStateUsing(fn($record) =>
                        $record->start->translatedFormat("l, H:i")
                            . "-" . $record->end->translatedFormat("H:i")
                            . " (" . round($record->start->diffInHours($record->end), 2) . "h)"
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('necessity')
                    ->label(__("Necessity"))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_user')
                    ->numeric()
                    ->label(__("Max. User"))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('team')
                    ->label("For all team member")
                    ->getStateUsing(fn($record) => $record->team ?: null)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                Tables\Columns\IconColumn::make('locked')
                    ->label(__("Locked"))
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__("Created"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__("Updated at"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make("shift_type_id")
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make("locked")
                    ->authorize("deleteAny")
                    ->label(__("Locked")."...")
                    ->deselectRecordsAfterCompletion()
                    ->form([
                        Forms\Components\Toggle::make("locked")
                            ->label(__("Locked")),
                    ])
                    ->action(fn($data, $records) => $records->toQuery()->update($data)),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
