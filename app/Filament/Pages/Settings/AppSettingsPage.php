<?php

namespace App\Filament\Pages\Settings;

use App\Filament\Clusters\Settings;
use App\Services\Translator;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\SettingsPage;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;

class AppSettingsPage extends SettingsPage
{
    protected static string $settings = \App\Settings\AppSettings::class;
    protected static ?string $cluster = Settings::class;
    protected static ?string $navigationIcon = "heroicon-o-cog-6-tooth";
    protected static ?string $slug = "system";
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function getNavigationLabel(): string {
        return __("System Settings");
    }
    public function getTitle(): string|Htmlable {
        return self::getNavigationLabel();
    }


    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Section::make(__("Event Details"))
                    ->collapsible()
                    ->collapsed()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make("event_name")
                            ->label("Event Name")
                            ->translateLabel(),
                        Forms\Components\DateTimePicker::make("event_start")
                            ->label("Start Date")
                            ->translateLabel()
                            ->seconds(false)
                            ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                        Forms\Components\DateTimePicker::make("event_end")
                            ->label("End Date")
                            ->translateLabel()
                            ->seconds(false)
                            ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                        Forms\Components\DateTimePicker::make("show_schedule_date")
                            ->label("Show Schedule from")
                            ->translateLabel()
                            ->seconds(false)
                            ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                    ]),
                Forms\Components\Section::make(__("LASSIE Settings"))
                    ->collapsible()
                    ->collapsed()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make("lassie_api_key")
                            ->label("LASSIE API Key")
                            ->string()
                            ->helperText(__("For retrieving Lost+Found DB")),
                        Forms\Components\TextInput::make("lassie_con_id")
                            ->label("LASSIE con_id")
                            ->nullable()
                            ->numeric(),
                        Forms\Components\TextInput::make("lassie_con_event_id")
                            ->label("LASSIE con_event_id")
                            ->nullable()
                            ->numeric(),
                        Forms\Components\Toggle::make("lost_found_enabled")
                            ->label("Lost & Found Enabled")
                            ->translateLabel(),
                    ]),
                Forms\Components\Section::make(__("Telegram Settings"))
                    ->collapsible()
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make("telegram_bot_name")
                            ->label("Telegram Bot Name")
                            ->nullable()
                            ->string(),
                        Forms\Components\TextInput::make("telegram_bot_token")
                            ->label("Telegram Bot Token")
                            ->nullable()
                            ->string(),
                    ]),
                Forms\Components\Section::make(__("DeepL Settings"))
                    ->collapsible()
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make("deepl_api_key")
                            ->label("DeepL API Key")
                            ->nullable()
                            ->string(),
                        Forms\Components\TextInput::make("deepl_usage")
                            ->label("DeepL Usage")
                            ->formatStateUsing(function() {
                                $used = app(Translator::class)->getUsage()?->character?->count ?? "?";
                                $max  = app(Translator::class)->getUsage()?->character?->limit ?? "?";
                                return $used . " / " . $max . " (" . number_format($used/$max*100, 2) . "%)";
                            })
                            ->readOnly()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make("deepl_source_lang")
                            ->string()
                            ->required(),
                        Forms\Components\TextInput::make("deepl_target_lang")
                            ->string()
                            ->required(),
                    ]),
            ]);
    }
}
