<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SigFormResource\Pages;
use App\Filament\Resources\SigFormResource\Widgets\FilledForms;
use App\Models\SigFilledForm;
use App\Models\SigForm;
use App\Models\UserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SigFormResource extends Resource
{
    protected static ?string $model = SigForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil';

    protected static ?string $navigationGroup = 'SIG';

    protected static ?int $navigationSort = 110;

    public static function can(string $action, ?Model $record = null): bool {
        return auth()->user()->permissions()->contains('manage_forms');
    }

    public static function getLabel(): ?string {
        return __('Form');
    }

    public static function getPluralLabel(): ?string {
        return __('Forms');
    }

    public static function getNavigationBadge(): ?string {
        if(!Route::is("filament.*"))
            return null;

        return SigFilledForm::where('approved', 0)->count() ?: null;
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                self::getNameFieldSet(),
                self::getSlugFieldSet(),
                self::getRolesFieldSet(),
                self::getFormClosedFieldSet(),
                self::getSigEventFieldSet(),
                self::getFormDefinitionFieldSet(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns(self::getTableColumns())
            ->actions([
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
            'edit' => Pages\EditSigForm::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array {
        return [
            FilledForms::class,
        ];
    }

    private static function getTableColumns(): array {
        return [
            Tables\Columns\TextColumn::make('slug')
                ->label('Slug')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->label('Name (' . __('German') . ')')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('name_en')
                ->label('Name (' . __('English') . ')')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('sigEvent.name')
                ->label('SIG')
                ->searchable()
                ->sortable()
                ->limit(50),
            Tables\Columns\TextColumn::make('sig_filled_forms_count')
                ->label('Filled')
                ->translateLabel()
                ->counts('sigFilledForms'),
            Tables\Columns\TextColumn::make('sig_filled_forms_approval_needed_count')
                ->label('To be approved')
                ->translateLabel()
                ->getStateUsing(function (SigForm $record) {
                    return $record->sigFilledForms->where('approved', 0)->count();
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
                        ->alphaDash()
                        ->maxLength(255)
                        ->inlineLabel()
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
                        ->relationship('userRoles')
                        ->label('')
                        ->options(function () {
                            if (auth()->user()->isAdmin()) {
                                return UserRole::all()->pluck('title', 'id');
                            } else {
                                return auth()->user()->roles()->pluck('title', 'user_roles.id');
                            }
                        })
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

    private static function getSigEventFieldSet(): Forms\Components\Component {
        return
            Forms\Components\Fieldset::make('assigned_sig')
                ->label('Assigned SIG')
                ->translateLabel()
                ->schema([
                    Forms\Components\Select::make('sig_event_id')
                        ->label('')
                        ->relationship('sigEvent', 'name', fn(Builder $query) => $query->orderBy('name')->with('sigHost'))
                        ->getOptionLabelFromRecordUsing(function (Model $record) {
                            return $record->name . ' - ' . $record->sigHost->name;
                        })
                        ->searchable()
                        ->preload(),
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
}
