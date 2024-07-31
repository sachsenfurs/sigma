<?php

namespace App\Filament\Pages\Settings;

use App\Enums\Permission;
use App\Filament\Clusters\Settings;
use App\Settings\ArtShowSettings;
use App\Settings\ChatSettings;
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

class ChatSettingsPage extends SettingsPage
{
    protected static string $settings = ChatSettings::class;
    protected static ?string $cluster = Settings::class;
    protected static ?string $navigationIcon = "heroicon-o-chat-bubble-left";
    protected static ?string $slug = "chats";
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function getNavigationLabel(): string {
        return __("Chat Settings");
    }
    public function getTitle(): string|Htmlable {
        return self::getNavigationLabel();
    }

    public static function canAccess(): bool {
        return Gate::allows("chatSettings", self::$settings);
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
                    ])
            ]);
    }
}
