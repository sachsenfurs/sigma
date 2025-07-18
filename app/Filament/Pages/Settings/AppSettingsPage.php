<?php

namespace App\Filament\Pages\Settings;

use App\Filament\Clusters\Settings;
use App\Filament\Traits\HasActiveIcon;
use App\Services\Translator;
use App\Settings\AppSettings;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;
use Intervention\Image\Laravel\Facades\Image;

class AppSettingsPage extends SettingsPage
{
    use HasActiveIcon;
    protected static string $settings = AppSettings::class;
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

    public static function canAccess(): bool {
        return Gate::allows("appSettings", self::$settings);
    }

    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Section::make(__("Event Details"))
                    ->collapsed()
                    ->columns(3)
                    ->schema(array(
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
                        Forms\Components\DateTimePicker::make("sig_application_deadline")
                            ->label("SIG Application Deadline")
                            ->translateLabel()
                            ->seconds(false)
                            ->dehydrateStateUsing(fn($state) => Carbon::parse($state)),
                        Forms\Components\Grid::make("")
                            ->columns(2)
                            ->schema(array(
                                Forms\Components\Toggle::make("accept_sigs_after_deadline")
                                    ->label("Accept SIG applications after deadline")
                                    ->translateLabel()
                                    ->inline(false),
                                Forms\Components\Toggle::make("paid_only")
                                    ->label("Signup requires paid ticket")
                                    ->translateLabel()
                                    ->inline(false),
                            )),
                        Forms\Components\FileUpload::make("app_icon")
                            ->label(__("Upload Logo"))
                            ->image()
                            ->disk("public")
                            ->dehydrateStateUsing(function () {
                                if(Storage::disk("public")->exists("logo.png")) {
                                    $path    = Storage::disk("public")->path("logo.png");
                                    $image   = Image::read($path);
                                    if($image->width() > 300) {
                                        $image->scaleDown(300);
                                        $image->save();
                                    }
                                }
                            })
                            ->getUploadedFileNameForStorageUsing(
                                fn (UploadedFile $file): string => 'logo.png'
                            )
                            ->imageEditor()
                            ->formatStateUsing(fn() => array("logo.png")),
                    )),
                Forms\Components\Section::make("API Endpoints")
                    ->translateLabel()
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make("event_api")
                            ->label("Signage Event API")
                            ->translateLabel()
                            ->readOnly()
                            ->dehydrated(false)
                            ->formatStateUsing(fn() => URL::signedRoute("api.events"))
                            ->hintAction(function($state) {
                                return Action::make(__("open"))
                                      ->url($state)
                                      ->openUrlInNewTab();
                            }),
                        Forms\Components\TextInput::make("location_api")
                            ->label("Signage Location API")
                            ->translateLabel()
                            ->readOnly()
                            ->dehydrated(false)
                            ->formatStateUsing(fn() => route("api.locations"))
                            ->hintAction(function($state) {
                                return Action::make(__("open"))
                                      ->url($state)
                                      ->openUrlInNewTab();
                            }),
                        Forms\Components\TextInput::make("essentials_api")
                            ->label("Signage Essentials API")
                            ->translateLabel()
                            ->dehydrated(false)
                            ->formatStateUsing(fn() => route("api.essentials"))
                            ->hintAction(function($state) {
                                return Action::make(__("open"))
                                      ->url($state)
                                      ->openUrlInNewTab();
                            }),
                        Forms\Components\TextInput::make("socials_api")
                            ->label("Signage Socials API")
                            ->translateLabel()
                            ->readOnly()
                            ->dehydrated(false)
                            ->formatStateUsing(fn() => route("api.socials"))
                            ->hintAction(function($state) {
                                return Action::make(__("open"))
                                      ->url($state)
                                      ->openUrlInNewTab();
                            }),
                        Forms\Components\TextInput::make("artshow_items_api")
                            ->label("Artshow Items API")
                            ->translateLabel()
                            ->readOnly()
                            ->dehydrated(false)
                            ->formatStateUsing(fn() => URL::signedRoute("api.artshow_items"))
                            ->hintAction(function($state) {
                                return Action::make(__("open"))
                                      ->url($state)
                                      ->openUrlInNewTab();
                            }),
                    ]),
                Forms\Components\Section::make(__("LASSIE Settings"))
                    ->collapsed()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make("lassie_api_key")
                            ->label("LASSIE API Key")
                            ->string()
                            ->password()
                            ->revealable()
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
                        Forms\Components\Placeholder::make("export")
                            ->label("LASSIE Export")
                            ->hintAction(
                                Action::make("open")
                                    ->url(URL::temporarySignedRoute("lassie-export.index", now()->addHours(24)))
                                    ->openUrlInNewTab()
                            ),
                    ]),
                Forms\Components\Section::make(__("Telegram Settings"))
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make("telegram_bot_name")
                            ->label("Telegram Bot Name")
                            ->nullable()
                            ->string(),
                        Forms\Components\TextInput::make("telegram_bot_token")
                            ->label("Telegram Bot Token")
                            ->password()
                            ->revealable()
                            ->nullable()
                            ->string(),
                    ]),
                Forms\Components\Section::make(__("DeepL Settings"))
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make("deepl_api_key")
                            ->password()
                            ->revealable()
                            ->label("DeepL API Key")
                            ->nullable()
                            ->string(),
                        Forms\Components\TextInput::make("deepl_usage")
                            ->label("DeepL Usage")
                            ->formatStateUsing(function() {
                                return Cache::remember("deepl_usage", 300, function() {
                                    $usage = app(Translator::class)->getUsage();
                                    $used = $usage?->character?->count ?? 1;
                                    $max  = $usage?->character?->limit ?? 1;
                                    return $used . " / " . $max . " (" . number_format($used/$max*100, 2) . "%)";
                                });
                            })
                            ->readOnly()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make("deepl_source_lang")
                            ->string()
                            ->helperText(
                                new HtmlString('<a href="https://developers.deepl.com/docs/v/de/resources/supported-languages" target="_blank">'.__("Supported languages").'</a>')
                            )
                            ->required(),
                        Forms\Components\TextInput::make("deepl_target_lang")
                            ->string()
                            ->required(),
                    ]),
            ]);
    }
}
