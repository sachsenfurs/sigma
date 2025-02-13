<?php

namespace App\Filament\Pages\Settings;

use App\Filament\Clusters\Settings;
use App\Filament\Traits\HasActiveIcon;
use App\Settings\ChatSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

class ChatSettingsPage extends SettingsPage
{
    use HasActiveIcon;
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
