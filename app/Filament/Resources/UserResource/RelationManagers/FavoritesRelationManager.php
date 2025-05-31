<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\SigEventResource;
use App\Models\TimetableEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class FavoritesRelationManager extends RelationManager
{
    protected static string $relationship = 'favorites';
    protected static ?string $icon = 'heroicon-o-heart';

    public static function getPluralModelLabel(): ?string {
        return __("Favorites");
    }
    public static function getModelLabel(): ?string {
        return __("Favorite");
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Favorites");
    }

    protected function getTableHeading(): string|Htmlable|null {
        return __('Favorites');
    }
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->favorites()->count();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('timetable_entry_id')
                    ->label("Event")
                    ->translateLabel()
                    ->options(TimetableEntry::orderBy("start")->get()->keyBy("id")->map(fn($e) => $e->start->translatedFormat("l | H:i") . " | " . $e->sigEvent->name_localized))
                    ->required()
                    ->searchable()
                    ->columnSpanFull()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('timetableEntry.sigEvent.name')
            ->columns([
                Tables\Columns\TextColumn::make('timetableEntry.sigEvent.name_localized')
                    ->label("SIG")
                    ->translateLabel(),
                Tables\Columns\TextColumn::make("timetableEntry.start")
                    ->label("Event Start")
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Faved at")
                    ->translateLabel()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->recordUrl(fn(Model $record) => SigEventResource::getUrl("view", ['record' => $record->timetableEntry->sigEvent]))
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
