<?php

namespace App\Filament\Clusters\MessageCluster;

use App\Filament\Traits\HasActiveIcon;
use App\Models\Message;
use BackedEnum;
use Filament\Clusters\Cluster;
use Illuminate\Support\Facades\Cache;

class MessageCluster extends Cluster
{
    use HasActiveIcon;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string {
        return __("Messages");
    }

    public static function getNavigationGroup(): ?string {
        return __("Messages");
    }

    public static function getNavigationBadge(): ?string {
        return Cache::remember("filamentUnreadMessages".auth()->id(), 10, fn() => Message::unreadAdmin()->whereHas("chat", fn($query) => $query->involved())->count()) ?: null;
    }

}
