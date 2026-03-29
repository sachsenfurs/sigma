<?php

namespace App\Filament\Resources\SigEvents;

use App\Filament\Resources\Chats\ChatResource;
use App\Filament\Resources\DepartmentInfos\DepartmentInfoResource;
use App\Filament\Resources\SigTags\SigTagResource;
use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Checkbox;
use App\Filament\Resources\SigEvents\Pages\ListSigEvents;
use App\Filament\Resources\SigEvents\Pages\CreateSigEvent;
use App\Filament\Resources\SigEvents\Pages\ViewSigEvent;
use App\Filament\Resources\SigEvents\Pages\EditSigEvent;
use App\Filament\Resources\TimetableEntries\RelationManagers\TimetableEntriesRelationManager;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\KeyValue;
use App\Enums\Approval;
use App\Filament\Actions\TranslateAction;
use App\Filament\Clusters\SigManagement\SigManagementCluster;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\SigEvents\RelationManagers\DepartmentInfosRelationManager;
use App\Filament\Resources\SigEvents\RelationManagers\SigFormsRelationManager;
use App\Filament\Resources\SigEvents\RelationManagers\SigHostsRelationManager;
use App\Filament\Resources\SigEvents\RelationManagers\SigTimeslotsRelationManager;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigTag;
use App\Models\UserRole;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Colors\Color;
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
    protected static ?string $cluster = SigManagementCluster::class;
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedPresentationChartBar;
    protected static ?int $navigationSort = 1;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;


    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
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
                SelectFilter::make("approval")
                    ->label("Approval")
                    ->translateLabel()
                    ->options(Approval::class),
                SelectFilter::make("tags")
                    ->relationship("sigTags", "name")
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->description_localized),
                Filter::make("Timeslots")
                    ->query(fn(Builder $query) => $query->has("sigTimeslots", ">", 0)),
                Filter::make("Text Missing")
                    ->translateLabel()
                    ->query(fn(Builder $query) => $query
                        ->where("no_text", false)
                        ->where(function(Builder $query) {
                            $query->where("description", "")
                                ->orWhere("description_en", "")
                                ->orWhereNull(["description", "description_en"]);
                        })
                    ),
                SelectFilter::make("proof_read")
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
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    Approval::getBulkAction()
                        ->authorize("update"),
                    BulkAction::make("text")
                        ->authorize("update")
                        ->label("Text...")
                        ->icon("heroicon-o-document-text")
                        ->translateLabel()
                        ->schema([
                            Checkbox::make("text_confirmed")
                                ->label("Proof-Read")
                                ->translateLabel(),
                            Checkbox::make("no_text")
                                ->label("Text not mandatory")
                                ->translateLabel(),
                        ])
                        ->action(fn(array $data, Collection $records) => $records->each->update($data))
                ])
            ]);
    }

    public static function getNavigationBadge(): ?string {
        return SigManagementCluster::getNavigationBadge();
    }

    public static function getPages(): array {
        return [
            'index' => ListSigEvents::route('/'),
            'create' => CreateSigEvent::route('/create'),
            'view' => ViewSigEvent::route('/{record}'),
            'edit' => EditSigEvent::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array {
        return [
            TimetableEntriesRelationManager::class,
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
                    EditAction::make()
                        ->modalWidth(Width::SevenExtraLarge)
                    ),
            TextColumn::make('name')
                ->searchable(['name', 'name_en'])
                ->sortable(),
            TextColumn::make('sigHosts.name')
                ->label('Hosts')
                ->translateLabel(),
            ImageColumn::make('languages')
                ->label('Languages')
                ->translateLabel()
                ->view('filament.tables.columns.sig-event.flag-icon'),
            TextColumn::make('sigTags.description_localized')
                ->label('Tags')
                ->translateLabel()
                ->badge(),
            TextColumn::make('timetable_entries_count')
                ->label('In Schedule')
                ->translateLabel()
                ->counts('timetableEntries')
                ->sortable()
                ->toggleable(),
            IconColumn::make("description")
                ->boolean()
                ->label("Text")
                ->visible(fn(?Model $record) => auth()->user()->can("attach", SigEvent::class))
                ->sortable()
                ->toggleable()
                ->getStateUsing(fn(Model $record) => $record->isDescriptionPresent()),
            IconColumn::make("description_en")
                ->boolean()
                ->label("Text (EN)")
                ->visible(fn(?Model $record) => auth()->user()->can("attach", SigEvent::class))
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
            TextColumn::make("sig_timeslots_count")
                ->label(__("Time Slots"))
                ->translateLabel()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->counts("sigTimeslots")
                ->badge()
                ->color(fn($state) => $state > 0 ? Color::Green : Color::Gray),
            TextColumn::make("department_infos_count")
                ->label("Department Requirements")
                ->translateLabel()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->counts("departmentInfos")
                ->badge()
                ->color(fn($state) => $state > 0 ? Color::Green : Color::Gray)
                ->action(
                    ViewAction::make()
                        ->modalHeading(fn($record) => $record->name_localized)
                        ->schema([
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
                        ->modalWidth(Width::SevenExtraLarge),
                ),
        ];
    }

    private static function getSigNameFieldSet(): \Filament\Schemas\Components\Component {
        return
            Fieldset::make('name')
            ->label('SIG Details')
            ->translateLabel()
            ->columnSpanFull()
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->maxLength(255)
                    ->required()
                    ->suffixAction(fn($operation) => $operation != "view" ? TranslateAction::translateToPrimary('name_en', 'name')->authorize("create", SigEvent::class) : null)
                    ->maxLength(255)
                    ->inlineLabel()
                    ->columnSpanFull(),
                TextInput::make('name_en')
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
                Select::make("duration")
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

    private static function getSigTagsFieldSet(): \Filament\Schemas\Components\Component {
        return
            Fieldset::make('tags')
                ->label('Tags')
                ->translateLabel()
                ->columnSpanFull()
                ->schema([
                    Select::make('sigTags')
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

    private static function getSigLanguageFieldSet(): \Filament\Schemas\Components\Component {
        return
            Fieldset::make('languages')
                ->label('Languages')
                ->translateLabel()
                ->columnSpanFull()
                ->schema([
                    CheckboxList::make('languages')
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

    private static function getSigHostsFieldSet(): \Filament\Schemas\Components\Component {
        return
            Fieldset::make('hosts')
                ->label('SIG Hosts')
                ->columnSpanFull()
                ->schema([
                    Select::make('sigHosts')
                        ->label('')
                        ->options(SigHost::all()->pluck('name', 'id'))
                        ->relationship('sigHosts', 'name')
                        ->preload()
                        ->multiple()
                        ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId()) // formatting when user already present
                        ->getSearchResultsUsing(FormHelper::searchSigHostByNameAndRegId())
                        ->columnSpanFull()
                        ->createOptionModalHeading(__('Create Host'))
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Name')
                                ->translateLabel()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('reg_id')
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
                    Select::make("approval")
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

    private static function getSigRegistrationFieldSet(): \Filament\Schemas\Components\Component {
        return
            Fieldset::make('registration')
                ->label('Registration')
                ->translateLabel()
                ->columnSpanFull()
                ->schema([
                    Checkbox::make('reg_possible')
                        ->label('Allow Registrations for this Event')
                        ->translateLabel()
                        ->columnSpanFull()
                        ->afterStateUpdated(function(Set $set, Get $get, $state) {
                            if($state)
                                $set('sigTags', array_merge($get('sigTags'), [3]));
                        })
                        ->live(),
                    TextInput::make('max_regs_per_day')
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

    private static function getSigDescriptionFieldSet(): \Filament\Schemas\Components\Component {
        return
            Fieldset::make('description')
                ->label('Description')
                ->translateLabel()
                ->live()
                ->visible(fn(Get $get) => !$get('no_text'))
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    MarkdownEditor::make('description')
                        ->label('German')
                        ->translateLabel()
                        ->maxLength(65535)
                        ->hintAction(
                            fn($operation) => $operation != "view" ? TranslateAction::translateToPrimary('description_en', 'description')->authorize("attach", SigEvent::class) : null
                        )
                        ->columnSpan(["2xl" => 1, "default" => 2])
                        ->afterStateUpdated(fn(Set $set) => $set('text_confirmed', false)),
                    MarkdownEditor::make('description_en')
                        ->label('English')
                        ->translateLabel()
                        ->maxLength(65535)
                        ->hintAction(
                            fn($operation) => $operation != "view" ? TranslateAction::translateToSecondary('description', 'description_en')->authorize("attach", SigEvent::class) : null
                        )
                        ->columnSpan(["2xl" => 1, "default" => 2])
                        ->afterStateUpdated(fn(Set $set) => $set('text_confirmed', false)),
                    Checkbox::make("text_confirmed")
                        ->label("Proof-Read")
                        ->translateLabel(),
                ]);
    }

    private static function getAdditionalInfoFieldSet(): \Filament\Schemas\Components\Component {
        return Textarea::make('additional_info')
            ->label(__("Additional Information"))
            ->translateLabel()
            ->rows(6)
            ->maxLength(65535)
            ->autosize()
            ->columnSpanFull();
    }

    private static function getLastModifiedField(): TextEntry {
        return TextEntry::make("updated_at")
            ->label("Last Modified")
            ->translateLabel()
            ->inlineLabel()
            ->visible(fn(?Model $record) => $record)
            ->state(fn(?Model $record) => $record?->updated_at?->diffForHumans() ?? "")
            ->extraAttributes(fn(?Model $record) => [
                'title' => $record?->updated_at?->toDateTimeString() ?? ""
            ]);
    }

    private static function getTextMandatoryFieldSet() {
        return Fieldset::make("text")
            ->label("Text")
            ->translateLabel()
            ->schema([
                Checkbox::make("no_text")
                     ->label("Text not mandatory")
                     ->live()
                     ->translateLabel(),
            ])
            ->columnSpanFull()
            ->columnSpan(1);
    }

    private static function getAttributeSection() {
        return Section::make(__("Attributes"))
            ->columnSpanFull()
            ->schema([
                KeyValue::make("attributes")
                    ->nullable()
                    ->label(__("API Metadata")),
                Select::make("private_group_ids")
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
