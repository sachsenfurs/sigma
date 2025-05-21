<?php

namespace App\Filament\Pages\Settings;

use App\Filament\Clusters\Settings;
use App\Settings\PageHookSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

class PageHookSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = PageHookSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $slug = "pagehooks";

    protected static ?int $navigationSort = 10000;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

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

    public function form(Form $form): Form {
        return $form
            ->schema([
                Section::make(__("General"))
                       ->collapsible()
                       ->schema([
                            Toggle::make("show_in_source")
                                ->label("Show Hooks in source")
                                ->translateLabel()
                                ->helperText(__("Showing all page hooks in the source code as an HTML comment")),
                        ])
            ]);
    }

}
