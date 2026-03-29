<?php

namespace App\Filament\Resources\PageHooks;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\PageHooks\Pages\ManagePageHooks;
use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\PageHook;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class PageHookResource extends Resource
{
    protected static ?string $model = PageHook::class;
    protected static ?string $cluster = SettingsCluster::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-code-bracket';
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationGroup(): ?string {
        return __("Page Hooks");
    }
    public static function getNavigationLabel(): string {
        return __("Page Hooks");
    }

    public function getTitle(): string|Htmlable {
        return self::getNavigationLabel();
    }

    public static function canAccess(): bool {
        return auth()->user()?->hasPermission(Permission::MANAGE_SETTINGS, PermissionLevel::ADMIN) ?? false;
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                TextInput::make("id")
                    ->label("Identifier")
                    ->columnSpanFull()
                    ->unique(ignoreRecord: true),
                Section::make(__("Content"))
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('content')
                            ->label("Content")
                            ->autosize()
                            ->translateLabel()
                            ->required()
                            ->visible(fn(Get $get) => !$get("html")),
                        RichEditor::make('content')
                            ->label("Content")
                            ->translateLabel()
                            ->required()
                            ->dehydrateStateUsing(fn($state) => htmlspecialchars_decode($state))
                            ->visible(fn(Get $get) => $get("html")),
                        Textarea::make('content_en')
                            ->autosize()
                            ->label("Content (English)")
                            ->translateLabel()
                            ->visible(fn(Get $get) => !$get("html")),
                        RichEditor::make('content_en')
                            ->label("Content (English)")
                            ->dehydrateStateUsing(fn($state) => htmlspecialchars_decode($state))
                            ->translateLabel()
                            ->visible(fn(Get $get) => $get("html")),
                    ]),
                Toggle::make('html')
                    ->label("Enable HTML")
                    ->reactive()
                    ->translateLabel(),
                TextInput::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Identifier')
                    ->searchable(),
                TextColumn::make('content')
                    ->label("Content")
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('content_en')
                    ->label("Content (English)")
                    ->translateLabel()
                    ->searchable(),
                IconColumn::make('html')
                    ->label("HTML")
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('description')
                    ->label("Description")
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->modalWidth(Width::SevenExtraLarge),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => ManagePageHooks::route('/'),
        ];
    }
}
