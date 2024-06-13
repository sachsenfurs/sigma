<?php

namespace App\Filament\Resources;

use App\Enums\Approval;
use App\Filament\Actions\TranslateAction;
use App\Filament\Resources\SigEventResource\Pages;
use App\Models\SigEvent;
use App\Models\SigTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class SigEventResource extends Resource
{
    protected static ?string $model = SigEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $navigationGroup = 'SIG';

    protected static ?string $label = 'SIGs';

    protected static ?int $navigationSort = 1;

    public static function can(string $action, ?Model $record = null): bool {
        return auth()->user()->permissions()->contains('manage_sigs');
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                self::getSigNameFieldSet(),
                self::getSigHostFieldSet(),
                self::getSigLanguageFieldSet(),
                self::getSigTagsFieldSet(),
                self::getSigHostsFieldSet(),
                self::getSigRegistrationFieldSet(),
                self::getSigDescriptionFieldSet(),
                self::getAdditionalInfoFieldSet(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns(self::getTableColumns())
            ->defaultSort('approval')
            ->emptyStateHeading(__('No SIGs available'))
            ->filters([
                Tables\Filters\SelectFilter::make("approval")
                    ->label("Approval")
                    ->translateLabel()
                    ->options(Approval::class),
                Tables\Filters\SelectFilter::make("tags")
                    ->relationship("sigTags", "name")
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->description_localized),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getNavigationBadge(): ?string {
        if(!Route::is("filament.*") AND !Route::is("livewire.*"))
            return null;

        return SigEvent::whereApproval(Approval::PENDING)->count() ?: false;
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListSigEvents::route('/'),
            'create' => Pages\CreateSigEvent::route('/create'),
            'edit' => Pages\EditSigEvent::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array {
        return [
            TimetableEntryResource\RelationManagers\TimetableEntriesRelationManager::class,
        ];
    }

    private static function getTableColumns(): array {
        return [
            IconColumn::make("approval")
                 ->translateLabel()
                 ->width(1),
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('sigHosts.name')
                ->label('Hosts')
                ->translateLabel(),
            Tables\Columns\ImageColumn::make('languages')
                ->label('Languages')
                ->translateLabel()
                ->view('filament.tables.columns.sig-event.flag-icon'),
            Tables\Columns\TextColumn::make('sigTags.description_localized')
                ->label('Tags')
                ->translateLabel()
                ->badge(),
            Tables\Columns\TextColumn::make('timetable_entries_count')
                ->label('In Schedule')
                ->translateLabel()
                ->counts('timetableEntries')
                ->sortable(),
        ];
    }

    private static function getSigNameFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('name')
            ->label('SIG Details')
            ->translateLabel()
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('German')
                    ->translateLabel()
                    ->required()
                    ->suffixAction(
                        TranslateAction::translateToPrimary('name_en', 'name')
                    )
                    ->maxLength(255)
                    ->inlineLabel()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('name_en')
                    ->label('English')
                    ->translateLabel()
                    ->required()
                    ->suffixAction(
                        TranslateAction::translateToSecondary('name', 'name_en')
                    )
                    ->maxLength(255)
                    ->inlineLabel()
                    ->columnSpanFull(),
                Forms\Components\Select::make("duration")
                    ->label("Duration (Hours)")
                    ->translateLabel()
                    ->inlineLabel()
                    ->columnSpanFull()
                    ->required()
                    ->default(60)
                    ->options(collect(range(30, 360, 30))->mapWithKeys(fn($r) => [$r => $r / 60]))
            ])
            ->columnSpan(1);
    }

    private static function getSigTagsFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('tags')
                ->label('Tags')
                ->translateLabel()
                ->schema([
                    Forms\Components\Select::make('sigTags')
                        ->label('')
                        ->options(SigTag::all()->pluck('description', 'id'))
                        ->relationship('sigTags', 'description')
                        ->preload()
                        ->multiple()
                        ->columnSpanFull()
                        ->createOptionModalHeading(__('Create Tag'))
                        ->createOptionForm(fn($form) => SigTagResource::form($form)),
                ])
                ->columnSpan(1)
                ->visible(auth()->user()->can('manage_sigs'));
    }

    private static function getSigLanguageFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('languages')
                ->label('Languages')
                ->translateLabel()
                ->schema([
                    Forms\Components\CheckboxList::make('languages')
                        ->columnSpanFull()
                        ->label('')
                        ->options([
                            'de' => __('German'),
                            'en' => __('English'),
                        ])
                        ->bulkToggleable(),
                ])
                ->columnSpan(1);
    }

    private static function getSigHostFieldSet(): Forms\Components\Component
    {
        return
            Forms\Components\Fieldset::make('hosts')
                ->label('SIG Hosts')
                ->schema(
                    [
                        Forms\Components\Select::make('sigHosts')
                            ->label('')
                            ->options(SigHost::all()->pluck('name', 'id'))
                            ->relationship('sigHosts', 'name')
                            ->preload()
                            ->multiple()
                            ->columnSpanFull()
                            ->createOptionModalHeading(__('Create Host'))
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->translateLabel()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('reg_id')
                                    ->label('Reg ID')
                                    ->translateLabel()
                                    ->numeric(),
                            ])
                            ->createOptionUsing(function ($data) {
                                $sigHost = SigHost::create([
                                    'name' => $data['name'],
                                    'reg_id' => $data['reg_id'] ?? null,
                                ]);

                                // TODO: update timestamps

                                return $sigHost->id ?? null;
                            })
                            ->live()
                            ->hintAction(
                                function($state) {
                                    if(filled($state)) {
                                        return Forms\Components\Actions\Action::make("edit")
                                            ->label("Edit")
                                            ->translateLabel()
                                            ->url(SigHostResource::getUrl("edit", ['record' => $state]));
                                    }
                                }
                            )
                            ->columnSpanFull(),
                        Forms\Components\Select::make("approval")
                            ->label("Approval")
                            ->translateLabel()
                            ->required()
                            ->default(Approval::PENDING)
                            ->columnSpanFull()
                            ->options(Approval::class),
                    ]
                )
                ->translateLabel()
                ->columnSpan(1)
            ->visible(auth()->user()->can('manage_sigs'));
    }

    private static function getSigRegistrationFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('registration')
                ->label('Registration')
                ->translateLabel()
                ->schema([
                    Forms\Components\Checkbox::make('reg_possible')
                        ->label('Allow Registrations for this Event')
                        ->translateLabel()
                        ->columnSpanFull()
                        ->afterStateUpdated(function(Forms\Set $set, Get $get, $state) {
                            if($state)
                                $set('sigTags', array_merge($get('sigTags'), [3]));
                        })
                        ->live(),
                    Forms\Components\TextInput::make('max_regs_per_day')
                        ->label('Registrations per day')
                        ->translateLabel()
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->visible(fn (Get $get) => $get('reg_possible') === true)
                        ->columnSpanFull(),
                ])
                ->columnSpan(1)
                ->visible(auth()->user()->can('manage_sigs'));
    }

    private static function getSigDescriptionFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('description')
                ->label('Description')
                ->translateLabel()
                ->columns(2)
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('German')
                        ->translateLabel()
                        ->hintAction(
                            TranslateAction::translateToPrimary('description_en', 'description')
                        )
                        ->columnSpan(["2xl" => 1, "default" => 2])
                        ->rows(8),
                    Forms\Components\Textarea::make('description_en')
                        ->label('English')
                        ->translateLabel()
                        ->hintAction(
                            TranslateAction::translateToSecondary('description', 'description_en')
                        )
                        ->columnSpan(["2xl" => 1, "default" => 2])
                        ->rows(8),
                ]);
    }

    private static function getAdditionalInfoFieldSet(): Forms\Components\Component {
        return Forms\Components\Textarea::make('additional_info')
            ->label(__("Additional Information"))
            ->translateLabel()
            ->rows(6)
            ->autosize()
            ->columnSpanFull();
    }
}
