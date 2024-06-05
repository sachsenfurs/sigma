<?php

namespace App\Filament\Pages\Settings;

use App\Filament\Clusters\Settings;
use App\Settings\ArtShowSettings;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;

class ArtShowSettingsPage extends SettingsPage
{
    protected static string $settings = ArtShowSettings::class;
    protected static ?string $cluster = Settings::class;
    protected static ?string $navigationIcon = "heroicon-o-paint-brush";
    protected static ?string $slug = "artshow";
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function getNavigationLabel(): string {
        return __("Art Show Settings");
    }
    public function getTitle(): string|Htmlable {
        return self::getNavigationLabel();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__("General"))
                    ->collapsible()
                    ->schema([
                        Toggle::make("enabled"),
                        DateTimePicker::make("item_deadline")
                            ->label("Item Submission Deadline")
                            ->translateLabel()
                            ->seconds(false)
                            ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                        DateTimePicker::make("show_items_date")
                            ->label("Show Items on public page")
                            ->translateLabel()
                            ->seconds(false)
                            ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),

                    ])
            ]);
    }
}
