<?php

namespace App\Filament\Pages\Settings;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Filament\Traits\HasActiveIcon;
use App\Settings\ArtShowSettings;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

class ArtShowSettingsPage extends SettingsPage
{
    use HasActiveIcon;
    protected static string $settings = ArtShowSettings::class;
    protected static ?string $cluster = SettingsCluster::class;
    protected static string | BackedEnum | null $navigationIcon = "heroicon-o-paint-brush";
    protected static ?string $slug = "artshow";
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function getNavigationLabel(): string {
        return __("Art Show Settings");
    }
    public function getTitle(): string|Htmlable {
        return self::getNavigationLabel();
    }

    public static function canAccess(): bool {
        return Gate::allows("artshowSettings", self::$settings);
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
                        Fieldset::make("Art Show Items Signup")
                            ->translateLabel()
                            ->columnSpanFull()
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
                                Toggle::make("paid_only")
                                      ->label("Signup requires paid ticket")
                                      ->translateLabel(),
                            ]),
                        Fieldset::make("Bids")
                                ->translateLabel()
                                ->columnSpanFull()
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
