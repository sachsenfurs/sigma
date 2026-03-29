<?php

namespace App\Filament\Resources\SigTimeslotResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Helper\FormHelper;
use App\Models\SigAttendee;
use App\Models\SigTimeslot;
use Closure;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SigAttendeeRelationManager extends RelationManager
{
    protected static string $relationship = 'sigAttendees';
    protected static ?string $inverseRelationship = "sigTimeslot";

    public static function getModelLabel(): ?string {
        return __("Attendee");
    }

    public static function getPluralLabel(): ?string {
        return __("Attendees");
    }
    public function getTablePluralModelLabel(): ?string {
        return __("Attendees");
    }

    public static function getPluralModelLabel(): ?string {
        return __("Attendees");
    }

    public function form(Schema $schema): Schema {
        return $schema
            ->components([
//                Forms\Components\TextInput::make('user.name')
//                    ->required()
//                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table {
        return $table
            ->heading(__("Attendees"))
            ->columns([
                TextColumn::make('user.reg_id')
                    ->label("Reg Number")
                    ->translateLabel(),
                TextColumn::make('user.name')
                    ->label("User")
                    ->translateLabel(),
                TextColumn::make("created_at")
                    ->label("Registered at")
                    ->translateLabel()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->selectable()
            ->headerActions([
                CreateAction::make()
                    ->label("Add")
                    ->authorize(function() {
                        return auth()->user()->can("adminCreate", [SigAttendee::class, $this->ownerRecord]);
                    })
                    ->translateLabel()
                    ->schema([
                        Select::make("user_id")
                            ->relationship("user","name")
                            ->searchable()
                            ->getSearchResultsUsing(FormHelper::searchUserByNameAndRegId())
                            ->rules([
                                fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                    if($this->ownerRecord instanceof SigTimeslot) {
                                        if($this->ownerRecord->sigAttendees->count() >= $this->ownerRecord->max_users)
                                            $fail(__("Attendee limit reached"));
                                    }
                                },
                                fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                    if (SigAttendee::where('user_id', $value)
                                                   ->where('sig_timeslot_id', $this->ownerRecord->id)
                                                   ->exists()) {
                                        $fail(__("Already signed up"));
                                    }
                                }
                            ]),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        DateTimePicker::make("created_at")
                    ]),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
