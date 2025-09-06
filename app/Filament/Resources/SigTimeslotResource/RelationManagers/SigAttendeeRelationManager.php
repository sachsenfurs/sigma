<?php

namespace App\Filament\Resources\SigTimeslotResource\RelationManagers;

use App\Filament\Helper\FormHelper;
use App\Models\SigAttendee;
use App\Models\SigTimeslot;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
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

    public function form(Form $form): Form {
        return $form
            ->schema([
//                Forms\Components\TextInput::make('user.name')
//                    ->required()
//                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table {
        return $table
            ->heading(__("Attendees"))
            ->columns([
                Tables\Columns\TextColumn::make('user.reg_id')
                    ->label("Reg Number")
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label("User")
                    ->translateLabel(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Registered at")
                    ->translateLabel()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->selectable()
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label("Add")
                    ->authorize(function() {
                        return auth()->user()->can("adminCreate", [SigAttendee::class, $this->ownerRecord]);
                    })
                    ->translateLabel()
                    ->form([
                        Forms\Components\Select::make("user_id")
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
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\DateTimePicker::make("created_at")
                    ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
