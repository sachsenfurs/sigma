<?php

namespace App\Filament\Resources;

use App\Enums\Approval;
use App\Filament\Resources\SigFormResource\Pages;
use App\Filament\Resources\SigFormResource\RelationManagers\SigFilledFormsRelationManager;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigFilledForm;
use App\Models\SigForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'SIG';

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

    public static function form(Form $form): Form {
        return $form
            ->schema([
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListSigForms::route('/'),
            'create' => Pages\CreateSigForm::route('/create'),
            'view' => Pages\ViewSigForm::route('/{record}'),
            'edit' => Pages\EditSigForm::route('/{record?}/edit'),
        ];
    }

    private static function getTableColumns(): array {
        return [
            Tables\Columns\TextColumn::make('slug')
                ->label('Slug')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('name_en')
                ->label('Name (English)')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('sigEvents.name')
                ->label('SIG')
                ->searchable()
                ->sortable()
                ->badge()
                ->limit(50),
            Tables\Columns\TextColumn::make('sig_filled_forms_count')
                ->label('Filled')
                ->translateLabel()
                ->counts('sigFilledForms'),
            Tables\Columns\TextColumn::make('sig_filled_forms_approval_needed_count')
                ->label('To be approved')
                ->translateLabel()
                ->getStateUsing(function (SigForm $record) {
                    return $record->sigFilledForms->where('approval', Approval::PENDING)->count();
                }),
        ];
    }

    private static function getNameFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('name')
                ->label('Name')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('German')
                        ->translateLabel()
                        ->required()
                        ->maxLength(255)
                        ->inlineLabel()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('name_en')
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

    private static function getSlugFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('slug')
                ->label('Slug')
                ->schema([
                    Forms\Components\TextInput::make('slug')
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

    private static function getRolesFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('roles')
                ->label('User Roles')
                ->translateLabel()
                ->schema([
                    Forms\Components\Select::make('userRoles')
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

    private static function getFormClosedFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('form_closed')
                ->label('Close form')
                ->translateLabel()
                ->schema([
                    Forms\Components\Checkbox::make('form_closed')
                        ->label('Form is closed')
                        ->translateLabel()
                        ->helperText(__('If the form is closed, no new forms can be submitted and existing forms can no longer be edited.'))
                        ->columnSpanFull(),
                ])
                ->columnSpanFull();
    }

    private static function getSigEventsFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('assigned_sig')
                ->label('Assigned SIG')
                ->translateLabel()
                ->schema([
                    Forms\Components\Select::make('sig_events')
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

    private static function getFormDefinitionFieldSet(): Forms\Components\Component {
        return Forms\Components\Fieldset::make('form_definition')
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

    private static function getBlockTextInput(): Forms\Components\Builder\Block {
        return Forms\Components\Builder\Block::make('text')
            ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
            ->label(function (?array $state): string {
                if ($state === null) {
                    return __('Text input');
                }
                return $state['label'] ?? __('Text input');
            })
            ->schema([
                Forms\Components\TextInput::make('label')
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
                Forms\Components\TextInput::make('label_en')
                    ->label('Label (English)')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->alphaDash()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('help_text')
                    ->label('Help text')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('help_text_en')
                    ->label('Help text (English)')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\Checkbox::make('required')
                    ->label('Required')
                    ->translateLabel(),
            ]);
    }

    private static function getBlockTextarea(): Forms\Components\Builder\Block {
        return Forms\Components\Builder\Block::make('textarea')
            ->icon('heroicon-o-bars-3-bottom-left')
            ->label(function (?array $state): string {
                if ($state === null) {
                    return __('Textarea');
                }
                return $state['label'] ?? __('Textarea');
            })
            ->schema([
                Forms\Components\TextInput::make('label')
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
                Forms\Components\TextInput::make('label_en')
                    ->label('Label (English)')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->alphaDash()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('help_text')
                    ->label('Help text')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('help_text_en')
                    ->label('Help text (English)')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\Checkbox::make('required')
                    ->label('Required')
                    ->translateLabel(),
            ]);
    }

    private static function getBlockFileUpload(): Forms\Components\Builder\Block {
        return Forms\Components\Builder\Block::make('file_upload')
            ->icon('heroicon-o-document')
            ->label(function (?array $state): string {
                if ($state === null) {
                    return __('File upload');
                }
                return $state['label'] ?? __('File upload');
            })
            ->schema([
                Forms\Components\TextInput::make('label')
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
                Forms\Components\TextInput::make('label_en')
                    ->label('Label (English)')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->alphaDash()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('help_text')
                    ->label('Help text')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('help_text_en')
                    ->label('Help text (English)')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\Checkbox::make('required')
                    ->label('Required')
                    ->translateLabel(),
            ]);
    }

    private static function getBlockCheckbox(): Forms\Components\Builder\Block {
        return Forms\Components\Builder\Block::make('checkbox')
            ->icon('heroicon-o-check-circle')
            ->label(function (?array $state): string {
                if ($state === null) {
                    return __('Checkbox');
                }
                return $state['label'] ?? __('Checkbox');
            })
            ->schema([
                Forms\Components\TextInput::make('label')
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
                Forms\Components\TextInput::make('label_en')
                    ->label('Label (English)')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->alphaDash()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('help_text')
                    ->label('Help text')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('help_text_en')
                    ->label('Help text (English)')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\Checkbox::make('required')
                    ->label('Required')
                    ->translateLabel(),
            ]);
    }

    private static function getBlockSelect(): Forms\Components\Builder\Block {
        return Forms\Components\Builder\Block::make('select')
            ->icon('heroicon-o-queue-list')
            ->label(function (?array $state): string {
                if ($state === null) {
                    return __('Select');
                }
                return $state['label'] ?? __('Select');
            })
            ->schema([
                Forms\Components\TextInput::make('label')
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
                Forms\Components\TextInput::make('label_en')
                    ->label('Label (English)')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->alphaDash()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Checkbox::make('required')
                    ->label('Required')
                    ->translateLabel(),
                Forms\Components\TextInput::make('help_text')
                    ->label('Help text')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('help_text_en')
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
                        Forms\Components\Builder\Block::make('option')
                            ->icon('heroicon-o-queue-list')
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return __('Option');
                                }
                                return $state['label'] ?? __('Option');
                            })
                            ->schema([
                                Forms\Components\TextInput::make('label')
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
                                Forms\Components\TextInput::make('label_en')
                                    ->label('Label (English)')
                                    ->translateLabel()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('value')
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
