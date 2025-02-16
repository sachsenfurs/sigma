<?php

namespace App\Filament\Resources;

use App\Enums\Approval;
use App\Filament\Actions\TranslateAction;
use App\Filament\Clusters\SigManagement;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\SigEventResource\Pages;
use App\Filament\Resources\SigEventResource\RelationManagers\DepartmentInfosRelationManager;
use App\Filament\Resources\SigEventResource\RelationManagers\SigFormsRelationManager;
use App\Filament\Resources\SigEventResource\RelationManagers\SigTimeslotsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\SigHostsRelationManager;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigTag;
use App\Models\UserRole;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class SigEventResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = SigEvent::class;
    protected static ?string $label = "SIGs";
    protected static ?string $cluster = SigManagement::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?int $navigationSort = 1;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;


    public static function form(Form $form): Form {
        return $form
            ->schema([
                self::getSigNameFieldSet(),
                self::getSigHostsFieldSet(),
                self::getSigLanguageFieldSet(),
                self::getSigTagsFieldSet(),
                self::getSigRegistrationFieldSet(),
                self::getTextMandatoryFieldSet(),
                self::getSigDescriptionFieldSet(),
                self::getAdditionalInfoFieldSet(),
                self::getLastModifiedField(),
                self::getAttributeSection(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn($query) => $query->with(["sigHosts", "sigHosts.user"]))
            ->columns(self::getTableColumns())
            ->recordClasses(function(Model $record) {
                if($record->is_private)
                    return 'bg-purple-950';
                return null;
            })
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
                    ),
                Tables\Filters\SelectFilter::make("proof_read")
                    ->label("Proof-Read")
                    ->translateLabel()
                    ->options([
                        true => __("Yes"),
                        false => __("No"),
                    ])
                    ->default(null)
                    ->query(fn(Builder $query, $state) => ($state['value'] ?? null) != null ? $query->where("text_confirmed", $state['value'])->where("no_text", false) : $query),
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
                Tables\Actions\BulkActionGroup::make([
                    Approval::getBulkAction(),
                    BulkAction::make("text")
                        ->label("Text...")
                        ->icon("heroicon-o-document-text")
                        ->translateLabel()
                        ->form([
                            Forms\Components\Checkbox::make("text_confirmed")
                                ->label("Proof-Read")
                                ->translateLabel(),
                            Forms\Components\Checkbox::make("no_text")
                                ->label("Text not mandatory")
                                ->translateLabel(),
                        ])
                        ->action(fn(array $data, Collection $records) => $records->each->update($data))
                ])
            ]);
    }

    public static function getNavigationBadge(): ?string {
        return SigManagement::getNavigationBadge();
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
            DepartmentInfosRelationManager::class,
            SigHostsRelationManager::class,
            SigFormsRelationManager::class,
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
                ->searchable(['name', 'name_en'])
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
                ->getStateUsing(fn(Model $record) => $record->isDescriptionPresent()),
            IconColumn::make("description_en")
                ->boolean()
                ->label("Text (EN)")
                ->visible(fn(?Model $record) => auth()->user()->can("update", $record))
                ->sortable()
                ->toggleable()
                ->getStateUsing(fn(Model $record) => $record->isDescriptionEnPresent()),
            IconColumn::make("text_confirmed")
                ->boolean()
                ->label("Proof-Read")
                ->translateLabel()
                ->getStateUsing(fn(Model $record) => $record->no_text ? true : $record->text_confirmed)
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make("sig_timeslots_count")
                ->label(__("Time Slots"))
                ->translateLabel()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->counts("sigTimeslots")
                ->badge()
                ->color(fn($state) => $state > 0 ? Color::Green : Color::Gray),
            Tables\Columns\TextColumn::make("department_infos_count")
                ->label("Department Requirements")
                ->translateLabel()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->counts("departmentInfos")
                ->badge()
                ->color(fn($state) => $state > 0 ? Color::Green : Color::Gray)
                ->action(
                    Tables\Actions\ViewAction::make()
                        ->modalHeading(fn($record) => $record->name_localized)
                        ->infolist([
                            TextEntry::make("additional_info")
                                ->label("Additional Information")
                                ->translateLabel(),
                            RepeatableEntry::make("departmentInfos")
                                ->label("Linked Departments")
                                ->translateLabel()
                                ->schema([
                                    TextEntry::make("userRole.name_localized")
                                        ->label("")
                                        ->hintIcon("heroicon-o-pencil-square")
                                        ->hintAction(fn($record) => Action::make("edit")->url(DepartmentInfoResource::getUrl("edit", ['record' => $record]))),
                                    TextEntry::make("additional_info")
                                        ->listWithLineBreaks()
                                        ->formatStateUsing(fn($state) => new HtmlString(nl2br(e($state))))
                                        ->label(""),
                                ])
                        ])
                        ->modalWidth(MaxWidth::SevenExtraLarge),
                ),
        ];
    }

    private static function getSigNameFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('name')
            ->label('SIG Details')
            ->translateLabel()
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
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

    private static function getSigHostsFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('hosts')
                ->label('SIG Hosts')
                ->schema([
                    Forms\Components\Select::make('sigHosts')
                        ->label('')
                        ->options(SigHost::all()->pluck('name', 'id'))
                        ->relationship('sigHosts', 'name')
                        ->preload()
                        ->multiple()
                        ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId()) // formatting when user already present
                        ->getSearchResultsUsing(FormHelper::searchUserByNameAndRegId())
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
                    Actions::make([
                        ChatResource::getCreateChatAction(fn(Get $get, Component $livewire) => SigHost::find($livewire->data['sigHosts'][0] ?? null)?->user?->id ?? 0),
                    ])
                    ->visibleOn("edit")
                    ->hidden(fn(Get $get) => empty($get('sigHosts')))
                ])
                ->translateLabel()
                ->columnSpan(1);
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
                    Forms\Components\Checkbox::make('group_registration_enabled')
                        ->label('Allow the first registered attendee to manage (Add/Remove) attendees for his timeslot')
                        ->translateLabel()
                        ->columnSpanFull()
                        ->visible(fn (Get $get) => $get('reg_possible') === true),
                ])
                ->columnSpan(1);
    }

    private static function getSigDescriptionFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('description')
                ->label('Description')
                ->translateLabel()
                ->live()
                ->visible(fn(Get $get) => !$get('no_text'))
                ->columns(2)
                ->schema([
                    Forms\Components\MarkdownEditor::make('description')
                        ->label('German')
                        ->translateLabel()
                        ->maxLength(65535)
                        ->hintAction(
                            fn($operation) => $operation != "view" ? TranslateAction::translateToPrimary('description_en', 'description')->authorize("attach", SigEvent::class) : null
                        )
                        ->columnSpan(["2xl" => 1, "default" => 2])
                        ->afterStateUpdated(fn(Forms\Set $set) => $set('text_confirmed', false)),
                    Forms\Components\MarkdownEditor::make('description_en')
                        ->label('English')
                        ->translateLabel()
                        ->maxLength(65535)
                        ->hintAction(
                            fn($operation) => $operation != "view" ? TranslateAction::translateToSecondary('description', 'description_en')->authorize("attach", SigEvent::class) : null
                        )
                        ->columnSpan(["2xl" => 1, "default" => 2])
                        ->afterStateUpdated(fn(Forms\Set $set) => $set('text_confirmed', false)),
                    Forms\Components\Checkbox::make("text_confirmed")
                        ->label("Proof-Read")
                        ->translateLabel(),
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

    private static function getLastModifiedField(): Forms\Components\Placeholder {
        return Forms\Components\Placeholder::make("updated_at")
            ->label("Last Modified")
            ->translateLabel()
            ->inlineLabel()
            ->visible(fn(?Model $record) => $record)
            ->content(fn(?Model $record) => $record?->updated_at?->diffForHumans() ?? "")
            ->extraAttributes(fn(?Model $record) => [
                'title' => $record?->updated_at?->toDateTimeString() ?? ""
            ]);
    }

    private static function getTextMandatoryFieldSet() {
        return Forms\Components\Fieldset::make("text")
            ->label("Text")
            ->translateLabel()
            ->schema([
                Forms\Components\Checkbox::make("no_text")
                     ->label("Text not mandatory")
                     ->live()
                     ->translateLabel(),
            ])
            ->columnSpan(1);
    }

    private static function getAttributeSection() {
        return Forms\Components\Section::make(__("Attributes"))
            ->schema([
                Forms\Components\KeyValue::make("attributes")
                    ->nullable()
                    ->label(__("API Metadata")),
                Forms\Components\Select::make("private_group_ids")
                    ->options(UserRole::all()->pluck("name_localized", "id"))
                    ->multiple()
                    ->dehydrateStateUsing(function ($state) {
                        $array = collect($state)->map(fn($i) => (int)$i)->toArray();
                        return count($array) == 0 ? null : $array;
                    })
                    ->visible(auth()->user()->isAdmin())
            ])
            ->collapsed()
            ->columns(1)
            ->columnSpan(false);
    }
}
