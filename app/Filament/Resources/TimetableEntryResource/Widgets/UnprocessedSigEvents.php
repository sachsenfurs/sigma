<?php

namespace App\Filament\Resources\TimetableEntryResource\Widgets;

use App\Filament\Resources\SigEventResource;
use App\Filament\Resources\TimetableEntryResource\Pages\CreateTimetableEntry;
use App\Models\SigEvent;
use App\Models\TimetableEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class UnprocessedSigEvents extends TableWidget
{
    protected int | string | array $columnSpan = "full";

    protected function getTableHeading(): string|Htmlable|null {
        return __("Unprocessed SIG Events");
    }

    /**
     * @return string|null
     */
    public static function getHeading(): ?string {
        return "AAAA";
    }

    /**
     * Conditionally show/hide widget
     * @return bool
     */
    public static function canView(): bool {
        return SigEvent::unprocessed()->count() > 0;
    }

    public function table(Table $table): Table {
        return $table
            ->query(
                SigEvent::unprocessed()
            )
            ->columns([
                IconColumn::make("approval")
                    ->translateLabel()
                    ->sortable()
                    ->width(1),
                TextColumn::make("name")
                    ->label("SIG Name")
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make("duration")
                    ->label("Duration (Hours)")
                    ->translateLabel()
                    ->formatStateUsing(fn($state) => $state/60)
                    ->sortable(),
                TextColumn::make("sigTags.description_localized")
                    ->label("Tags")
                    ->translateLabel()
                    ->badge(),
            ])
            ->actions([
                CreateTimetableEntry::getCreateAction(CreateAction::make())
                    ->button(),
                Action::make("edit")
                    ->authorize("edit", SigEvent::class)
                    ->model(SigEvent::class)
                    ->label("Edit")
                    ->translateLabel()
                    ->url(fn(?Model $record) => SigEventResource::getUrl("edit", ['record' => $record])),
                ViewAction::make()
                    ->model(SigEvent::class)
                    ->label(false)
                    ->icon(false)
                    ->modalHeading(__("SIG Details"))
                    ->infolist([
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                TextEntry::make("name_localized")
                                    ->label("SIG Name")
                                    ->translateLabel(),
                                TextEntry::make("sigHosts.name")
                                    ->label("SIG Host")
                                    ->translateLabel(),
                                TextEntry::make("duration_hours"),
                            ]),
                        TextEntry::make("description_localized")
                            ->label("Description")
                            ->translateLabel(),
                        TextEntry::make("additional_info"),
                        TextEntry::make("sigTags.description_localized")
                            ->label("Tags")
                            ->translateLabel()
                            ->badge(),
                    ]),
            ])
            ->recordAction(
                ViewAction::class
            )
            ->defaultPaginationPageOption(10)
            ;
    }

}
