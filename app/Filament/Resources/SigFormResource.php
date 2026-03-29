<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SigFormResource\Pages\ListSigForms;
use App\Filament\Resources\SigFormResource\Pages\CreateSigForm;
use App\Filament\Resources\SigFormResource\Pages\ViewSigForm;
use App\Filament\Resources\SigFormResource\Pages\EditSigForm;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Builder\Block;
use App\Enums\Approval;
use App\Filament\Resources\SigFormResource\Pages;
use App\Filament\Resources\SigFormResource\RelationManagers\SigFilledFormsRelationManager;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigFilledForm;
use App\Models\SigForm;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SigFormResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = SigForm::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-pencil-square';

    protected static string | \UnitEnum | null $navigationGroup = 'SIG';

    protected static ?int $navigationSort = 20;

    public static function getLabel(): ?string {
        return __('Form');
    }

    public static function getPluralLabel(): ?string {
        return __('Forms');
    }

    public static function getNavigationBadge(): ?string {
        if(!Route::is("filament.*"))
            return null;

        return Cache::remember("sigform-unapproved-badge", 10, fn() => SigFilledForm::where('approval', Approval::PENDING)->count()) ?: null;
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                self::getNameFieldSet(),
                self::getSlugFieldSet(),
                self::getRolesFieldSet(),
                self::getFormClosedFieldSet(),
                self::getSigEventsFieldSet(),
                self::getFormDefinitionFieldSet(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns(self::getTableColumns())
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => ListSigForms::route('/'),
            'create' => CreateSigForm::route('/create'),
            'view' => ViewSigForm::route('/{record}'),
            'edit' => EditSigForm::route('/{record?}/edit'),
        ];
    }

    private static function getTableColumns(): array {
        return [
            TextColumn::make('slug')
                ->label('Slug')
                ->searchable()
                ->sortable(),
            TextColumn::make('name')
                ->label('Name')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            TextColumn::make('name_en')
                ->label('Name (English)')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            TextColumn::make('sigEvents.name')
                ->label('SIG')
                ->searchable()
                ->sortable()
                ->badge()
                ->limit(50),
            TextColumn::make('sig_filled_forms_count')
                ->label('Filled')
                ->translateLabel()
                ->counts('sigFilledForms'),
            TextColumn::make('sig_filled_forms_approval_needed_count')
                ->label('To be approved')
                ->translateLabel()
                ->getStateUsing(function (SigForm $record) {
                    return $record->sigFilledForms->where('approval', Approval::PENDING)->count();
                }),
        ];
    }

    private static function getNameFieldSet(): Component {
        return
            Fieldset::make('name')
                ->label('Name')
                ->columnSpanFull()
                ->schema([
                    TextInput::make('name')
                        ->label('German')
                        ->translateLabel()
                        ->required()
                        ->maxLength(255)
                        ->inlineLabel()
                        ->columnSpanFull(),
                    TextInput::make('name_en')
                        ->label('English')
                        ->translateLabel()
                        ->required()
                        ->maxLength(255)
                        ->inlineLabel()
                        ->columnSpanFull()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                            if (($get('slug') ?? '') !== Str::slug($old)) {
                                return;
                            }
                            $set('slug', Str::slug($state));
                        }),
                ])
                ->columnSpan(1);
    }

    private static function getSlugFieldSet(): Component {
        return
            Fieldset::make('slug')
                ->label('Slug')
                ->columnSpanFull()
                ->schema([
                    TextInput::make('slug')
                        ->label('Slug')
                        ->translateLabel()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->alphaDash()
                        ->maxLength(255)
                        ->inlineLabel()
                        ->afterStateUpdated(function($state, ?Model $record) {
                            if($record AND $state != $record?->slug)
                                redirect(self::getUrl("edit", ['record' => $state]));
                        })
                        ->columnSpanFull(),
                ])
                ->columnSpan(1);
    }

    private static function getRolesFieldSet(): Component {
        return
            Fieldset::make('roles')
                ->label('User Roles')
                ->translateLabel()
                ->columnSpanFull()
                ->schema([
                    Select::make('userRoles')
                        ->relationship('userRoles', "name")
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized)
                        ->label('')
                        ->preload()
                        ->multiple()
                        ->columnSpanFull()
                        ->live(),
                ])
                ->columnSpan(1);
    }

    private static function getFormClosedFieldSet(): Component {
        return
            Fieldset::make('form_closed')
                ->label('Close form')
                ->translateLabel()
                ->schema([
                    Checkbox::make('form_closed')
                        ->label('Form is closed')
                        ->translateLabel()
                        ->helperText(__('If the form is closed, no new forms can be submitted and existing forms can no longer be edited.'))
                        ->columnSpanFull(),
                ])
                ->columnSpanFull();
    }

    private static function getSigEventsFieldSet(): Component {
        return
            Fieldset::make('assigned_sig')
                ->label('Assigned SIG')
                ->translateLabel()
                ->schema([
                    Select::make('sig_events')
                        ->label('')
                        ->multiple()
                        ->relationship('sigEvents', 'name', fn(Builder $query) => $query->orderBy('name')->with('sigHosts'))
                        ->getOptionLabelFromRecordUsing(function (Model $record) {
                            return $record->name_localized . ' - ' . $record->sigHosts->pluck("name")->join(", ");
                        })
                        ->searchable(['name', 'name_en'])
                ])
                ->columnSpanFull();
    }

    private static function getFormDefinitionFieldSet(): Component {
        return Fieldset::make('form_definition')
            ->label('Form definition')
            ->translateLabel()
            ->columnSpanFull()
            ->schema([
                Forms\Components\Builder::make('form_definition')
                    ->label('')
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->blockNumbers(false)
                    ->blocks([
                        self::getBlockTextInput(),
                        self::getBlockTextarea(),
                        self::getBlockFileUpload(),
                        self::getBlockCheckbox(),
                        self::getBlockSelect(),
                    ]),
            ]);
    }

    private static function getBlockTextInput(): Block {
        return Block::make('text')
            ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
            ->label(function (?array $state): string {
                if ($state === null) {
                    return __('Text input');
                }
                return $state['label'] ?? __('Text input');
            })
            ->schema([
                TextInput::make('label')
                    ->label('Label')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('name') ?? '') !== Str::slug($old, '_')) {
                            return;
                        }
                        $set('name', Str::slug($state, '_'));
                    }),
                TextInput::make('label_en')
                    ->label('Label (English)')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->alphaDash()
                    ->required()
                    ->maxLength(255),
                TextInput::make('help_text')
                    ->label('Help text')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('help_text_en')
                    ->label('Help text (English)')
                    ->translateLabel()
                    ->maxLength(255),
                Checkbox::make('required')
                    ->label('Required')
                    ->translateLabel(),
            ]);
    }

    private static function getBlockTextarea(): Block {
        return Block::make('textarea')
            ->icon('heroicon-o-bars-3-bottom-left')
            ->label(function (?array $state): string {
                if ($state === null) {
                    return __('Textarea');
                }
                return $state['label'] ?? __('Textarea');
            })
            ->schema([
                TextInput::make('label')
                    ->label('Label')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('name') ?? '') !== Str::slug($old, '_')) {
                            return;
                        }
                        $set('name', Str::slug($state, '_'));
                    }),
                TextInput::make('label_en')
                    ->label('Label (English)')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->alphaDash()
                    ->required()
                    ->maxLength(255),
                TextInput::make('help_text')
                    ->label('Help text')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('help_text_en')
                    ->label('Help text (English)')
                    ->translateLabel()
                    ->maxLength(255),
                Checkbox::make('required')
                    ->label('Required')
                    ->translateLabel(),
            ]);
    }

    private static function getBlockFileUpload(): Block {
        return Block::make('file_upload')
            ->icon('heroicon-o-document')
            ->label(function (?array $state): string {
                if ($state === null) {
                    return __('File upload');
                }
                return $state['label'] ?? __('File upload');
            })
            ->schema([
                TextInput::make('label')
                    ->label('Label')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('name') ?? '') !== Str::slug($old, '_')) {
                            return;
                        }
                        $set('name', Str::slug($state, '_'));
                    }),
                TextInput::make('label_en')
                    ->label('Label (English)')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->alphaDash()
                    ->required()
                    ->maxLength(255),
                TextInput::make('help_text')
                    ->label('Help text')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('help_text_en')
                    ->label('Help text (English)')
                    ->translateLabel()
                    ->maxLength(255),
                Checkbox::make('required')
                    ->label('Required')
                    ->translateLabel(),
            ]);
    }

    private static function getBlockCheckbox(): Block {
        return Block::make('checkbox')
            ->icon('heroicon-o-check-circle')
            ->label(function (?array $state): string {
                if ($state === null) {
                    return __('Checkbox');
                }
                return $state['label'] ?? __('Checkbox');
            })
            ->schema([
                TextInput::make('label')
                    ->label('Label')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('name') ?? '') !== Str::slug($old, '_')) {
                            return;
                        }
                        $set('name', Str::slug($state, '_'));
                    }),
                TextInput::make('label_en')
                    ->label('Label (English)')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->alphaDash()
                    ->required()
                    ->maxLength(255),
                TextInput::make('help_text')
                    ->label('Help text')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('help_text_en')
                    ->label('Help text (English)')
                    ->translateLabel()
                    ->maxLength(255),
                Checkbox::make('required')
                    ->label('Required')
                    ->translateLabel(),
            ]);
    }

    private static function getBlockSelect(): Block {
        return Block::make('select')
            ->icon('heroicon-o-queue-list')
            ->label(function (?array $state): string {
                if ($state === null) {
                    return __('Select');
                }
                return $state['label'] ?? __('Select');
            })
            ->schema([
                TextInput::make('label')
                    ->label('Label')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('name') ?? '') !== Str::slug($old, '_')) {
                            return;
                        }
                        $set('name', Str::slug($state, '_'));
                    }),
                TextInput::make('label_en')
                    ->label('Label (English)')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->alphaDash()
                    ->required()
                    ->maxLength(255),
                Checkbox::make('required')
                    ->label('Required')
                    ->translateLabel(),
                TextInput::make('help_text')
                    ->label('Help text')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('help_text_en')
                    ->label('Help text (English)')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\Builder::make('options')
                    ->label('Options')
                    ->translateLabel()
                    ->collapsible()
                    ->columnSpanFull()
                    ->blockNumbers(false)
                    ->blocks([
                        Block::make('option')
                            ->icon('heroicon-o-queue-list')
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return __('Option');
                                }
                                return $state['label'] ?? __('Option');
                            })
                            ->schema([
                                TextInput::make('label')
                                    ->label('Label')
                                    ->translateLabel()
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                        if (($get('value') ?? '') !== Str::slug($old, '_')) {
                                            return;
                                        }
                                        $set('value', Str::slug($state, '_'));
                                    }),
                                TextInput::make('label_en')
                                    ->label('Label (English)')
                                    ->translateLabel()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('value')
                                    ->label('Value')
                                    ->translateLabel()
                                    ->alphaDash()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            SigFilledFormsRelationManager::class,
        ];
    }
}
