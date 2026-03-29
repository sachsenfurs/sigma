<?php

namespace App\Filament\Pages\Settings;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Filament\Traits\HasActiveIcon;
use App\Settings\ChatSettings;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

class ChatSettingsPage extends SettingsPage
{
    use HasActiveIcon;
    protected static string $settings = ChatSettings::class;
    protected static ?string $cluster = SettingsCluster::class;
    protected static string | BackedEnum | null $navigationIcon = "heroicon-o-chat-bubble-left";
    protected static ?string $slug = "chats";
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function getNavigationLabel(): string {
        return __("Chat Settings");
    }
    public function getTitle(): string|Htmlable {
        return self::getNavigationLabel();
    }

    public static function canAccess(): bool {
        return Gate::allows("chatSettings", self::$settings);
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
                    ])
            ]);
    }
}
