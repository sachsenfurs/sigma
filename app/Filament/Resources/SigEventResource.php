<?php

namespace App\Filament\Resources;

use App\Enums\Approval;
use App\Filament\Actions\TranslateAction;
use App\Filament\Resources\SigEventResource\Pages;
use App\Filament\Resources\SigEventResource\RelationManagers\SigTimeslotsRelationManager;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
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

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form {
        return $form
            ->schema([
                self::getSigNameFieldSet(),
                self::getSigHostsFieldSet(),
                self::getSigLanguageFieldSet(),
                self::getSigTagsFieldSet(),
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
            ->persistSortInSession()
            ->persistFiltersInSession()
            ->filters([
                Tables\Filters\SelectFilter::make("approval")
                    ->label("Approval")
                    ->translateLabel()
                    ->options(Approval::class),
                Tables\Filters\SelectFilter::make("tags")
                    ->relationship("sigTags", "name")
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->description_localized),
                Tables\Filters\Filter::make("Timeslots")
                    ->query(fn(Builder $query) => $query->has("sigTimeslots", ">", 0)),
                Tables\Filters\Filter::make("Text Missing")
                    ->translateLabel()
                    ->query(fn(Builder $query) => $query
                        ->where(function(Builder $query) {
                            $query->where("description", "")
                                ->orWhere("description_en", "")
                                ->orWhereNull(["description", "description_en"]);
                        })
                    )
            ])
            ->recordUrl(fn(Model $record) =>
                auth()->user()->can("update", $record)
                ? SigEventResource::getUrl("edit", ['record' => $record])
                : SigEventResource::getUrl('view', ['record' => $record])
            )
            ->actions([
                //
            ])
            ->bulkActions([
                Approval::getBulkAction(),
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
            'view' => Pages\ViewSigEvent::route('/{record}'),
            'edit' => Pages\EditSigEvent::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array {
        return [
            TimetableEntryResource\RelationManagers\TimetableEntriesRelationManager::class,
            SigTimeslotsRelationManager::class,
        ];
    }

    private static function getTableColumns(): array {
        return [
            IconColumn::make("approval")
                ->translateLabel()
                ->width(1)
                ->action(
                    Tables\Actions\EditAction::make()
                        ->modalWidth(MaxWidth::SevenExtraLarge)
                    ),
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
                ->sortable()
                ->toggleable(),
            IconColumn::make("description")
                ->boolean()
                ->label("Text")
                ->visible(fn(?Model $record) => auth()->user()->can("update", $record))
                ->sortable()
                ->toggleable()
                ->getStateUsing(fn(Model $record) => filled($record->description)),
            IconColumn::make("description_en")
                ->boolean()
                ->label("Text (EN)")
                ->visible(fn(?Model $record) => auth()->user()->can("update", $record))
                ->sortable()
                ->toggleable()
                ->getStateUsing(fn(Model $record) => filled($record->description_en)),
            Tables\Columns\TextColumn::make("sig_timeslots_count")
                ->label(__("Time Slots"))
                ->translateLabel()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->counts("sigTimeslots")
                ->badge()
                ->color(fn($state) => $state > 0 ? Color::Green : Color::Gray),
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
                    ->maxLength(255)
                    ->required()
                    ->suffixAction(fn($operation) => $operation != "view" ? TranslateAction::translateToPrimary('name_en', 'name')->authorize("create", SigEvent::class) : null)
                    ->maxLength(255)
                    ->inlineLabel()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('name_en')
                    ->label('English')
                    ->translateLabel()
                    ->maxLength(255)
                    ->required()
                    ->suffixAction(
                        fn($operation) => $operation != "view" ? TranslateAction::translateToSecondary('name', 'name_en')->authorize("create", SigEvent::class) : null
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
                        ->label('Tags')
                        ->options(SigTag::all()->pluck('description', 'id'))
                        ->relationship('sigTags', 'description')
                        ->preload()
                        ->multiple()
                        ->columnSpanFull()
                        ->createOptionModalHeading(__('Create Tag'))
                        ->createOptionForm(fn($form) => SigTagResource::form($form)),
                ])
                ->columnSpan(1);
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

    private static function getSigHostsFieldSet(): Forms\Components\Component
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

                                return $sigHost->id ?? null;
                            })
                            ->live()
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
                ->columnSpan(1);
    }

    private static function getSigDescriptionFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('description')
                ->label('Description')
                ->translateLabel()
                ->columns(2)
                ->schema([
                    Forms\Components\MarkdownEditor::make('description')
                        ->label('German')
                        ->translateLabel()
                        ->maxLength(65535)
                        ->hintAction(
                            fn($operation) => $operation != "view" ? TranslateAction::translateToPrimary('description_en', 'description')->authorize("attach", SigEvent::class) : null
                        )
                        ->columnSpan(["2xl" => 1, "default" => 2]),
                    Forms\Components\MarkdownEditor::make('description_en')
                        ->label('English')
                        ->translateLabel()
                        ->maxLength(65535)
                        ->hintAction(
                            fn($operation) => $operation != "view" ? TranslateAction::translateToSecondary('description', 'description_en')->authorize("attach", SigEvent::class) : null
                        )
                        ->columnSpan(["2xl" => 1, "default" => 2]),
                ]);
    }

    private static function getAdditionalInfoFieldSet(): Forms\Components\Component {
        return Forms\Components\Textarea::make('additional_info')
            ->label(__("Additional Information"))
            ->translateLabel()
            ->rows(6)
            ->maxLength(65535)
            ->autosize()
            ->columnSpanFull();
    }
}
