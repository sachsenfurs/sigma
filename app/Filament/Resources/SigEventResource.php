<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SigEventResource\Pages;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigTag;
use App\Models\SigTranslation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SigEventResource extends Resource
{
    protected static ?string $model = SigEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $navigationGroup = 'SIG';

    protected static ?string $label = 'SIGs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::getSigNameFieldSet(),
                self::getSigLanguageFieldSet(),
                self::getSigHostFieldSet(),
                self::getSigLocationFieldSet(),
                self::getSigRegistrationFieldSet(),
                self::getSigTagsFieldSet(),
                self::getSigDescriptionFieldSet(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sigHost.name')
                    ->label('Host')
                    ->translateLabel()
                    ->searchable()
                    ->formatStateUsing(function (Model $record) {
                        $regNr = $record->sigHost->reg_id ? ' (' . __('Reg Number') . ': ' . $record->sigHost->reg_id . ')' : '';
                        return $record->sigHost->name . $regNr;
                    })
                    ->sortable(),
                Tables\Columns\ImageColumn::make('languages')
                    ->label('Languages')
                    ->translateLabel()
                    ->view('filament.tables.columns.sig-event.flag-icon'),
                Tables\Columns\TextColumn::make('sigLocation.name')
                    ->label('Location')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sigTags.description_localized')
                    ->label('Tags')
                    ->translateLabel()
                    ->badge(),
                Tables\Columns\TextColumn::make('timetable_entries_count')
                    ->label('In Schedule')
                    ->translateLabel()
                    ->counts('timetableEntries')
                    ->sortable(),
            ])
            ->defaultSort('timetable_entries_count', 'desc')
            ->emptyStateHeading(__('No SIGs available'))
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSigEvents::route('/'),
            'create' => Pages\CreateSigEvent::route('/create'),
            'edit' => Pages\EditSigEvent::route('/{record}/edit'),
        ];
    }

    private static function getSigNameFieldSet(): Forms\Components\Component
    {
        return
            Forms\Components\Fieldset::make('name')
            ->label('SIG Name')
            ->translateLabel()
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('German')
                    ->translateLabel()
                    ->required()
                    //->required(fn (Get $get) => in_array('de', $get('languages')) ?? false)
                    ->maxLength(255)
                    ->inlineLabel()
                    ->columnSpanFull(),
                Forms\Components\Group::make()
                    ->relationship('sigTranslation')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('English')
                            ->translateLabel()
                            ->formatStateUsing(function (SigTranslation $record = null) {
                                return $record->name ?? '';
                            })
                            ->required()
                            //->required(fn (Get $get) => in_array('en', $get('../languages')) ?? false)
                            ->maxLength(255)
                            ->inlineLabel()
                    ])
                    ->columnSpanFull(),
            ])
            ->columnSpan(1);
    }

    private static function getSigLanguageFieldSet(): Forms\Components\Component
    {
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
                        ->bulkToggleable()
                        ->required()
                        ->live(),
                ])
                ->columnSpan(1);
    }

    private static function getSigHostFieldSet(): Forms\Components\Component
    {
        return
            Forms\Components\Fieldset::make('host')
            ->label('SIG Host')
            ->translateLabel()
            ->schema([
                Forms\Components\Select::make('sig_host_id')
                    ->label('Host')
                    ->translateLabel()
                    ->relationship('sigHost', 'name', fn (Builder $query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->getOptionLabelFromRecordUsing(function (Model $record) {
                        $regNr = $record->reg_id ? " (" . __('Reg Number') . ": $record->reg_id)" : '';
                        return $record->name . $regNr;
                    })
                    ->createOptionUsing(function ($data) {
                        return SigHost::create([
                            'name' => $data['name'],
                            'reg_id' => $data['reg_id'] ?? null,
                        ]);
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('reg_id')
                            ->label('Reg Number')
                            ->translateLabel()
                            ->type('number')
                            ->minValue(1)
                            ->maxLength(10),
                    ])
                    ->columnSpanFull(),
            ])
            ->columnSpan(1)
            ->visible(auth()->user()->can('manage_events'));
    }

    private static function getSigLocationFieldSet(): Forms\Components\Component
    {
        return
            Forms\Components\Fieldset::make('location')
                ->label('SIG Location')
                ->translateLabel()
                ->schema([
                    Forms\Components\Select::make('sig_location_id')
                        ->label('Location')
                        ->translateLabel()
                        ->relationship('sigLocation', 'name', fn (Builder $query) => $query->orderBy('name'))
                        ->searchable()
                        ->preload()
                        ->getOptionLabelFromRecordUsing(function (Model $record) {
                            // If the location has a description, append it to the name
                            if ($record->description) {
                                return $record->name . ' - ' . $record->description;
                            }
                            return $record->name;
                        })
                        ->columnSpanFull(),
                ])
                ->columnSpan(1)
                ->visible(auth()->user()->can('manage_events'));
    }

    private static function getSigRegistrationFieldSet(): Forms\Components\Component
    {
        return
            Forms\Components\Fieldset::make('registration')
                ->label('Registration')
                ->translateLabel()
                ->schema([
                    Forms\Components\Checkbox::make('reg_possible')
                        ->label('Allow Registrations for this Event')
                        ->translateLabel()
                        ->columnSpanFull()
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
                ->visible(auth()->user()->can('manage_events'));
    }

    private static function getSigTagsFieldSet(): Forms\Components\Component
    {
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
                        ->live()
                        ->createOptionModalHeading(__('Create Tag'))
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Name')
                                ->translateLabel()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Fieldset::make('description')
                                ->label('Description')
                                ->translateLabel()
                                ->schema([
                                    Forms\Components\Textarea::make('description')
                                        ->label('German')
                                        ->translateLabel()
                                        ->rows(4),
                                    Forms\Components\Textarea::make('description_en')
                                        ->label('English')
                                        ->translateLabel()
                                        ->rows(4),
                                ]),
                        ])
                        ->createOptionUsing(function ($data) {
                            return SigTag::create([
                                'name' => $data['name'],
                                'description' => $data['description'] ?? null,
                                'description_en' => $data['description_en'] ?? null,
                            ]);
                        }),
                ])
                ->columnSpan(1)
                ->visible(auth()->user()->can('manage_events'));
    }

    private static function getSigDescriptionFieldSet(): Forms\Components\Component
    {
        return
            Forms\Components\Fieldset::make('description')
                ->label('Description')
                ->translateLabel()
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('German')
                        ->translateLabel()
                        //->required(fn (Get $get) => in_array('de', $get('languages')) ?? false)
                        ->rows(4)
                        ->columnSpanFull(),
                    Forms\Components\Group::make()
                        ->relationship('sigTranslation')
                        ->schema([
                            Forms\Components\Textarea::make('description')
                                ->label('English')
                                ->translateLabel()
                                ->formatStateUsing(function (SigTranslation $record = null) {
                                    return $record->description ?? '';
                                })
                                //->required(fn (Get $get) => in_array('en', $get('../languages')) ?? false)
                                ->rows(4),
                        ])
                        ->columnSpanFull(),
                ]);
    }
}
