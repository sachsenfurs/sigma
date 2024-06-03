<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRoleResource\Pages;
use App\Models\Permission;
use App\Models\UserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserRoleResource extends Resource
{
    protected static ?string $model = UserRole::class;

    protected static ?string $navigationGroup = "System";

    protected static ?int $navigationSort = 1200;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->permissions()->contains('manage_users');
    }

    public static function getLabel(): ?string
    {
        return __('User Role');
    }

    public static function getPluralLabel(): ?string
    {
        return __('User Roles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make("details")
                    ->label("User Details")
                    ->translateLabel()
                    ->schema([
                        self::getTitleField(),
                        self::getRegistrationSystemKeyField(),
                    ]),
                Forms\Components\Fieldset::make("colors")
                    ->label("Colors")
                    ->translateLabel()
                    ->columns(3)
                    ->schema([
                        self::getForegroundColorField(),
                        self::getBorderColorField(),
                        self::getBackgroundColorField(),
                    ]),
                self::getPermissionListField(),
            ]);
    }

    public static function table(Table $table): Table
    {
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserRoles::route('/'),
            'create' => Pages\CreateUserRole::route('/create'),
            'edit' => Pages\EditUserRole::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->label('User Role')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('users_count')
                ->label('User Count')
                ->translateLabel()
                ->counts('users')
                ->sortable(),
            Tables\Columns\TextColumn::make("registration_system_key")
                ->label("Reg Group ID")
//                ->formatStateUsing(fn(?string $state) => explode(",", $state))
                ->badge()
                ->separator()
                ->translateLabel(),
        ];
    }

    private static function getTitleField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('title')
            ->label('User Role')
            ->translateLabel()
            ->required()
            ->maxLength(255);
    }

    private static function getRegistrationSystemKeyField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('registration_system_key')
            ->label('Group keys')
            ->translateLabel()
            ->helperText(__('Group keys from registration system (seperated with a comma)'))
            ->maxLength(255);
    }

    private static function getForegroundColorField(): Forms\Components\Component
    {
        return Forms\Components\ColorPicker::make('fore_color')
            ->label('Foreground Color')
            ->translateLabel()
            ->required()
            ->default('#333333');
    }

    private static function getBorderColorField(): Forms\Components\Component
    {
        return Forms\Components\ColorPicker::make('border_color')
            ->label('Border Color')
            ->translateLabel()
            ->required()
            ->default('#666666');
    }

    private static function getBackgroundColorField(): Forms\Components\Component
    {
        return Forms\Components\ColorPicker::make('background_color')
            ->label('Background Color')
            ->translateLabel()
            ->required()
            ->default('#E6E6E6');
    }

    private static function getPermissionListField(): Forms\Components\Component
    {
        return Forms\Components\Section::make()
            ->heading(__('Permissions'))
            ->collapsible()
            ->collapsed()
            ->schema([
                Forms\Components\CheckboxList::make('permissions')
                    ->label('')
                    ->columns(4)
                    ->columnSpanFull()
                    ->bulkToggleable()
                    ->relationship('permissions', 'name')
                    ->options(fn() => collect(Permission::all()->pluck('name', 'id')))
                    ->descriptions(fn() => collect(Permission::all()->pluck('friendly_name', 'id'))),
            ]);
    }
}
