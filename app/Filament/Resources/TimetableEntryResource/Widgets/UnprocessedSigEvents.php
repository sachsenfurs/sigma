<?php

namespace App\Filament\Resources\TimetableEntryResource\Widgets;

use App\Filament\Resources\SigEventResource;
use App\Filament\Resources\TimetableEntryResource\Pages\CreateTimetableEntry;
use App\Models\SigEvent;
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
                CreateTimetableEntry::getCreateAction(CreateAction::make())
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
