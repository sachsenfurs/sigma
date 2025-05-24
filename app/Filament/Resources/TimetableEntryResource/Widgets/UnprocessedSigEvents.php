<?php

namespace App\Filament\Resources\TimetableEntryResource\Widgets;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Resources\SigEventResource;
use App\Filament\Resources\TimetableEntryResource\Pages\CreateTimetableEntry;
use App\Models\SigEvent;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

class UnprocessedSigEvents extends TableWidget
{
    protected int | string | array $columnSpan = "full";

    protected function getTableHeading(): string|Htmlable|null {
        return __("Unprocessed SIG Events");
    }

    public static function canView(): bool {
        return SigEvent::unprocessed()->count() > 0 AND auth()->user()->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ);
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
                TextColumn::make("name_localized")
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
                    ->authorize("update", SigEvent::class)
                    ->model(SigEvent::class)
                    ->label("Edit")
                    ->translateLabel()
                    ->url(fn(?Model $record) => SigEventResource::getUrl("edit", ['record' => $record])),
                ViewAction::make()
                    ->model(SigEvent::class)
                    ->label(false)
                    ->icon(false)
                    ->modalHeading(__("SIG Details"))
                    ->extraModalFooterActions([
                        EditAction::make()
                            ->visible(fn(Model $record) => Gate::check("update", $record))
                            ->url(fn(Model $record) => SigEventResource::getUrl('edit', ['record' => $record]))
                            ->outlined(),
                    ])
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
                                TextEntry::make("duration_hours")
                                    ->label("Duration")
                                    ->suffix(" h")
                                    ->translateLabel(),
                            ]),
                        TextEntry::make("description_localized")
                            ->label("Description")
                            ->translateLabel()
                            ->formatStateUsing(fn($state) => new HtmlString(nl2br(e($state)))),
                        TextEntry::make("additional_info")
                            ->label("Additional Information")
                            ->translateLabel(),
                        TextEntry::make("sigTags.description_localized")
                            ->label("Tags")
                            ->translateLabel()
                            ->badge(),
                    ]),
            ])
            ->recordAction(
            ViewAction::class
            )
            ->defaultPaginationPageOption(10);
    }

}
