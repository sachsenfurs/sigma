<?php

namespace App\Filament\Resources;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Clusters\Settings;
use App\Filament\Resources\PageHookResource\Pages;
use App\Models\PageHook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class PageHookResource extends Resource
{
    protected static ?string $model = PageHook::class;
    protected static ?string $cluster = Settings::class;
    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

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

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make("id")
                    ->label("Identifier")
                    ->columnSpanFull()
                    ->unique(ignoreRecord: true),
                Forms\Components\Section::make(__("Content"))
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label("Content")
                            ->autosize()
                            ->translateLabel()
                            ->required()
                            ->visible(fn(Forms\Get $get) => !$get("html")),
                        Forms\Components\RichEditor::make('content')
                            ->label("Content")
                            ->translateLabel()
                            ->required()
                            ->dehydrateStateUsing(fn($state) => htmlspecialchars_decode($state))
                            ->visible(fn(Forms\Get $get) => $get("html")),
                        Forms\Components\Textarea::make('content_en')
                            ->autosize()
                            ->label("Content (English)")
                            ->translateLabel()
                            ->visible(fn(Forms\Get $get) => !$get("html")),
                        Forms\Components\RichEditor::make('content_en')
                            ->label("Content (English)")
                            ->dehydrateStateUsing(fn($state) => htmlspecialchars_decode($state))
                            ->translateLabel()
                            ->visible(fn(Forms\Get $get) => $get("html")),
                    ]),
                Forms\Components\Toggle::make('html')
                    ->label("Enable HTML")
                    ->reactive()
                    ->translateLabel(),
                Forms\Components\TextInput::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Identifier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('content')
                    ->label("Content")
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('content_en')
                    ->label("Content (English)")
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\IconColumn::make('html')
                    ->label("HTML")
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->label("Description")
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth(MaxWidth::SevenExtraLarge),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ManagePageHooks::route('/'),
        ];
    }
}
