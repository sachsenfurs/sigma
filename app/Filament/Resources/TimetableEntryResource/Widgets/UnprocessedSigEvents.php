<?php

namespace App\Filament\Resources\TimetableEntryResource\Widgets;

use App\Filament\Resources\SigEventResource;
use App\Filament\Resources\TimetableEntryResource;
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
use Illuminate\Database\Eloquent\Model;

class UnprocessedSigEvents extends TableWidget
{
//    protected static string $view = 'filament.resources.timetable-entry-resource.widgets.unprocessed-sig-events';
    protected int | string | array $columnSpan = "full";

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
                    ->width(1),
                TextColumn::make("name")
                    ->label("SIG Name")
                    ->translateLabel(),
                TextColumn::make("duration_hours"),
                TextColumn::make("sigTags.description_localized")
                    ->badge(),
            ])
            ->actions([
                CreateAction::make()
                    ->model(TimetableEntry::class)
                    ->modelLabel(__("Timetable Entry"))
                    ->translateLabel()
                    ->form(
                        TimetableEntryResource\Pages\CreateTimetableEntry::getSchema()
                    )
                    ->createAnother(false)
                    ->fillForm(function(Model $record) {
                        return [
                            'sig_event_id' => $record->id,
                            'start' => now()->setMinute(0)->setSecond(0),
                            'end' => now()->addHours(2)->setMinute(0)->setSecond(0),
                        ];
                    })
                    ->button(),
                Action::make("edit")
                    ->model(SigEvent::class)
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
                                TextEntry::make("sigHost.name")
                                    ->label("SIG Host")
                                    ->translateLabel(),
                                TextEntry::make("duration_hours"),
                            ]),
                        TextEntry::make("description_localized")
                            ->label("Description")
                            ->translateLabel(),
                        TextEntry::make("sigTags.description_localized")
                            ->badge(),
                    ]),
            ])
            ->recordAction(
                ViewAction::class
            )
            ;
    }

}
