<?php

namespace App\Filament\Pages\Settings;

use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use App\Filament\Clusters\Settings;
use App\Filament\Traits\HasActiveIcon;
use App\Settings\DealerSettings;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

class DealerSettingsPage extends SettingsPage
{
    use HasActiveIcon;
    protected static string $settings = DealerSettings::class;
    protected static ?string $cluster = Settings::class;
    protected static string | \BackedEnum | null $navigationIcon = "heroicon-o-shopping-cart";
    protected static ?string $slug = "dealers";
    protected static ?\Filament\Pages\Enums\SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function getNavigationLabel(): string {
        return __("Dealer Settings");
    }
    public function getTitle(): string|Htmlable {
        return self::getNavigationLabel();
    }

    public static function canAccess(): bool {
        return Gate::allows("dealerSettings", self::$settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__("General"))
                   ->collapsible()
                   ->columnSpanFull()
                   ->schema([
                       Toggle::make("enabled")
                            ->label("Enabled")
                            ->translateLabel(),
                       DateTimePicker::make("signup_deadline")
                            ->label("Dealer Signup Deadline")
                            ->translateLabel()
                            ->native(false)
                            ->seconds(false)
                            ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                       DateTimePicker::make("show_dealers_date")
                            ->label("Show Dealers on public page")
                            ->translateLabel()
                            ->native(false)
                            ->seconds(false)
                            ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                       Toggle::make("paid_only")
                            ->label("Signup requires paid ticket")
                            ->translateLabel(),
                       Toggle::make("image_mandatory")
                            ->label("Dealer must upload an image")
                            ->translateLabel(),
                   ])
            ]);
    }
}
