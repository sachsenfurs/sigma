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

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form {
        return $form
            ->schema([
                self::getSigNameFieldSet(),
                self::getSigHostFieldSet(),
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
            ->filters([
                Tables\Filters\SelectFilter::make("approval")
                    ->label("Approval")
                    ->translateLabel()
                    ->options(Approval::class),
                Tables\Filters\SelectFilter::make("tags")
                    ->relationship("sigTags", "name")
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->description_localized),
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
            'view' => Pages\ViewSigEvent::route('/{record}'),
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
                    ->suffixAction(
                        TranslateAction::translateToPrimary('name_en', 'name')
                    )
                    ->maxLength(255)
                    ->inlineLabel()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('name_en')
                    ->label('English')
                    ->translateLabel()
                    ->maxLength(255)
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

    private static function getSigHostFieldSet(): Forms\Components\Component {
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
                    ->createOptionForm(fn($form) => SigHostResource::form($form))
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
            ])
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
                            TranslateAction::translateToPrimary('description_en', 'description')
                        )
                        ->columnSpan(["2xl" => 1, "default" => 2]),
                    Forms\Components\MarkdownEditor::make('description_en')
                        ->label('English')
                        ->translateLabel()
                        ->maxLength(65535)
                        ->hintAction(
                            TranslateAction::translateToSecondary('description', 'description_en')
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
