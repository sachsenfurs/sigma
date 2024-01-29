<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRoleResource\Pages;
use App\Filament\Resources\UserRoleResource\RelationManagers;
use App\Models\UserRole;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class UserRoleResource extends Resource
{
    protected static ?string $model = UserRole::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label(__('User Role'))
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('fore_color')
                    ->required()
                    ->label(__('Foreground Color'))
                    ->default('333333')
                    ->afterStateHydrated(function (ColorPicker $component, string $state) {
                        // Because the database stores the color without the leading #, we need to add it here.
                        $component->state('#' . Str::upper($state));
                    })
                    ->dehydrateStateUsing(function (ColorPicker $component, string $state) {
                        // Because the database stores the color without the leading #, we need to remove it here.
                        return Str::replace('#', '', $state);
                    }),
                Forms\Components\ColorPicker::make('border_color')
                    ->required()
                    ->label(__('Border Color'))
                    ->default('666666')
                    ->afterStateHydrated(function (ColorPicker $component, string $state) {
                        // Because the database stores the color without the leading #, we need to add it here.
                        $component->state('#' . Str::upper($state));
                    })
                    ->dehydrateStateUsing(function (ColorPicker $component, string $state) {
                        // Because the database stores the color without the leading #, we need to remove it here.
                        return Str::replace('#', '', $state);
                    }),
                Forms\Components\ColorPicker::make('background_color')
                    ->required()
                    ->label(__('Background Color'))
                    ->default('E6E6E6')
                    ->afterStateHydrated(function (ColorPicker $component, string $state) {
                        // Because the database stores the color without the leading #, we need to add it here.
                        $component->state('#' . Str::upper($state));
                    })
                    ->dehydrateStateUsing(function (ColorPicker $component, string $state) {
                        // Because the database stores the color without the leading #, we need to remove it here.
                        return Str::replace('#', '', $state);
                    }),
                Forms\Components\Checkbox::make('perm_manage_settings')
                    ->label(__('Manage Settings'))
                    ->default(0),
                Forms\Components\Checkbox::make('perm_manage_users')
                    ->label(__('Manage Users'))
                    ->default(0),
                Forms\Components\Checkbox::make('perm_manage_events')
                    ->label(__('Manage Events'))
                    ->default(0),
                Forms\Components\Checkbox::make('perm_manage_locations')
                    ->label(__('Manage Locations'))
                    ->default(0),
                Forms\Components\Checkbox::make('perm_manage_hosts')
                    ->label(__('Manage Hosts'))
                    ->default(0),
                Forms\Components\Checkbox::make('perm_post')
                    ->label(__('Manage Posts'))
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('User Role'))
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
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
}
