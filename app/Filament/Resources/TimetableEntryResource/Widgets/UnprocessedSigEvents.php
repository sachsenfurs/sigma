<?php

namespace App\Filament\Resources\TimetableEntryResource\Widgets;

use App\Filament\Resources\TimetableEntryResource;
use App\Models\SigEvent;
use App\Models\TimetableEntry;
use Filament\Tables\Actions\CreateAction;
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
                TextColumn::make("name")
                    ->label("SIG Name")
                    ->translateLabel(),
            ])
            ->actions([
                CreateAction::make("create")
                    ->model(TimetableEntry::class)
                    ->form(
                        TimetableEntryResource::getSchema()
                    )
                    ->createAnother(false)
                    ->fillForm(function(Model $record) {
                        return [
                            'sig_event_id' => $record->id,
                            'start' => now()->setMinute(0)->setSecond(0),
                            'end' => now()->addHours(2)->setMinute(0)->setSecond(0),
                        ];
                    })
                ,
            ]);
    }

}
