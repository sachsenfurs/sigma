<?php

namespace App\Filament\Pages\Settings;

use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use App\Filament\Clusters\Settings;
use App\Settings\PageHookSettings;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

class PageHookSettingsPage extends SettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = PageHookSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $slug = "pagehooks";

    protected static ?int $navigationSort = 10000;

    protected static ?\Filament\Pages\Enums\SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationGroup(): ?string {
        return __("Page Hooks");
    }

    public static function getNavigationLabel(): string {
        return __("Page Hook Settings");
    }

    public function getTitle(): string|Htmlable {
        return self::getNavigationLabel();
    }

    public static function canAccess(): bool {
        return Gate::allows("appSettings", self::$settings);
    }

    public function form(Schema $schema): Schema {
        return $schema
            ->components([
                Section::make(__("General"))
                       ->collapsible()
                       ->columnSpanFull()
                       ->schema([
                            Toggle::make("show_in_source")
                                ->label("Show Hooks in source")
                                ->translateLabel()
                                ->helperText(__("Showing all page hooks in the source code as an HTML comment")),
                        ])
            ]);
    }

}
