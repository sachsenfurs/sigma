<?php

namespace App\Filament\Pages\Settings;

use App\Enums\Permission;
use App\Filament\Clusters\Settings;
use App\Settings\ArtShowSettings;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

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

    public static function canAccess(): bool {
        return Gate::allows("artshowSettings", self::$settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__("General"))
                    ->collapsible()
                    ->schema([
                        Toggle::make("enabled")
                            ->label("Enabled")
                            ->translateLabel(),
                        Fieldset::make("Artshow Items Signup")
                            ->translateLabel()
                            ->columns(3)
                            ->schema([
                                DateTimePicker::make("item_deadline")
                                    ->label("Item Submission Deadline")
                                    ->translateLabel()
                                    ->required()
                                    ->native(false)
                                    ->seconds(false)
                                    ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                                DateTimePicker::make("show_items_date")
                                    ->label("Show Items on public page")
                                    ->translateLabel()
                                    ->required()
                                    ->native(false)
                                    ->seconds(false)
                                    ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                                TextInput::make("charity_min_percentage")
                                     ->label("Min. Charity Percentage")
                                     ->translateLabel()
                                     ->required()
                                     ->minValue(0)
                                     ->maxValue(100)
                                     ->numeric(),
                            ]),
                        Fieldset::make("Bids")
                                ->translateLabel()
                                ->schema([
                                    DateTimePicker::make("bid_start_date")
                                        ->label("Bidding enabled from")
                                        ->translateLabel()
                                        ->required()
                                        ->native(false)
                                        ->seconds(false)
                                        ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                                    DateTimePicker::make("bid_end_date")
                                        ->label("Bidding enabled to")
                                        ->translateLabel()
                                        ->required()
                                        ->native(false)
                                        ->seconds(false)
                                        ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                                    TextInput::make("max_bids_per_item")
                                         ->label("Max. Bids per Item")
                                         ->translateLabel()
                                         ->helperText(__("Set how many bids are possible before the item goes up for auction"))
                                         ->required()
                                         ->minValue(0)
                                         ->numeric(),
                                    Toggle::make("require_checkin")
                                        ->label("Require Checkin")
                                        ->translateLabel()
                                        ->helperText(__("Requires user to be checked in at the convention site before bidding is availabel")),
                                ]),
                    ])
            ]);
    }
}
