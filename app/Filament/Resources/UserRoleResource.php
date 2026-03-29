<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\UserRoleResource\Pages\ListUserRoles;
use App\Filament\Resources\UserRoleResource\Pages\CreateUserRole;
use App\Filament\Resources\UserRoleResource\Pages\EditUserRole;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Resources\UserRoleResource\Pages;
use App\Filament\Resources\UserRoleResource\RelationManagers\UserRelationManager;
use App\Filament\Traits\HasActiveIcon;
use App\Models\UserRole;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;

class UserRoleResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = UserRole::class;

    protected static string | \UnitEnum | null $navigationGroup = "System";

    protected static ?int $navigationSort = 1200;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-identification';

    public static function getLabel(): ?string {
        return __('User Role');
    }

    public static function getPluralLabel(): ?string {
        return __('User Roles');
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Fieldset::make("details")
                    ->label("User Details")
                    ->translateLabel()
                    ->columnSpanFull()
                    ->schema([
                        self::getNameField(),
                        self::getNameEnField(),
                        self::getRegistrationSystemKeyField(),
                        self::getChatActivatedField(),
                    ]),
                Fieldset::make("colors")
                    ->label("Colors")
                    ->translateLabel()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        self::getForegroundColorField(),
                        self::getBorderColorField(),
                        self::getBackgroundColorField(),
                    ]),
                self::getPermissionListField(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns(self::getTableColumns())
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            UserRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListUserRoles::route('/'),
            'create' => CreateUserRole::route('/create'),
            'edit' => EditUserRole::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array {
        return [
            TextColumn::make('name_localized')
                ->label('User Role')
                ->translateLabel()
                ->searchable(['name', 'name_en'])
                ->badge()
                ->color(fn($record) => Color::generateV3Palette($record->background_color))
                ->sortable(),
            TextColumn::make('users_count')
                ->label('User Count')
                ->translateLabel()
                ->counts('users')
                ->sortable(),
            TextColumn::make("registration_system_key")
                ->label("Reg Group ID")
                ->badge()
                ->separator()
                ->translateLabel(),
        ];
    }

    private static function getNameField(): Component {
        return TextInput::make('name')
            ->label('User Role')
            ->translateLabel()
            ->required()
            ->maxLength(255);
    }

    private static function getNameEnField(): Component {
        return TextInput::make('name_en')
            ->label('User Role (English)')
            ->translateLabel()
            ->maxLength(255);
    }

    private static function getRegistrationSystemKeyField(): Component {
        return TextInput::make('registration_system_key')
            ->label('Group keys')
            ->translateLabel()
            ->helperText(__('Group keys from registration system (seperated with a comma)'))
            ->maxLength(255);
    }

    private static function getChatActivatedField(): Component {
        return Checkbox::make('chat_activated')
            ->label('Chat activated')
            ->translateLabel()
            ->helperText(__('If a UserRole (Department) is available for Chats'));
    }

    private static function getForegroundColorField(): Component {
        return ColorPicker::make('fore_color')
            ->label('Foreground Color')
            ->translateLabel()
            ->required()
            ->default('#333333');
    }

    private static function getBorderColorField(): Component {
        return ColorPicker::make('border_color')
            ->label('Border Color')
            ->translateLabel()
            ->required()
            ->default('#666666');
    }

    private static function getBackgroundColorField(): Component {
        return ColorPicker::make('background_color')
            ->label('Background Color')
            ->translateLabel()
            ->required()
            ->default('#E6E6E6');
    }

    private static function getPermissionListField(): Component {
        return Section::make()
            ->heading(__('Permissions'))
            ->collapsible()
            ->collapsed()
            ->columnSpanFull()
            ->schema([
                Repeater::make("permissions")
                    ->grid(2)
                    ->columns()
                    ->relationship()
                    ->schema([
                        Select::make("permission")
                            ->options(Permission::class)
                            ->required()
                            ->fixIndistinctState()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                        Select::make("level")
                            ->options(PermissionLevel::class)
                            ->default(PermissionLevel::NO)
                            ->selectablePlaceholder(false)
                            ->required(),
                    ])
            ]);
    }
}
