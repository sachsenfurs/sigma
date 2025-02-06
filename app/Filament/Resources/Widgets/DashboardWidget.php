<?php

namespace App\Filament\Resources\Widgets;

use App\Enums\ChatStatus;
use App\Filament\Resources\ChatResource;
use App\Filament\Resources\Ddas\ArtshowItemResource;
use App\Filament\Resources\Ddas\DealerResource;
use App\Filament\Resources\SigEventResource;
use App\Models\Chat;
use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\Dealer;
use App\Models\SigEvent;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

class DashboardWidget extends BaseWidget
{
    protected function getStats(): array {
        $stats = [];

        if(Gate::check("viewAny", Chat::class)) {
            $chatCount = ChatResource::getNavigationBadge();
            $stats[] = Stat::make(__("Unread messages"), $chatCount ?? 0)
                ->value($chatCount > 0 ? new HtmlString('<span class="text-red-500">' . $chatCount . '</span>') : 0)
                ->icon(ChatResource::getNavigationIcon())
                ->description(__("Chats open: :count", ['count' => Chat::where("status", ChatStatus::OPEN)->count()]))
                ->url(ChatResource::getUrl());
        }

        if(Gate::check("viewAny", SigEvent::class))
            $stats[] = Stat::make(__('Unconfirmed SIGs'), SigEventResource::getNavigationBadge() ?? 0)
                ->description(__("Total Events: :count", ['count' => SigEvent::count()]))
                ->icon(SigEventResource::getNavigationIcon())
                ->url(SigEventResource::getUrl());

        if(Gate::check("viewAny", ArtshowItem::class))
            $stats[] = Stat::make(__("Unconfirmed Art Show Items"), ArtshowItemResource::getNavigationBadge() ?? 0)
                ->description(__("Total Art Show Items: :count", ['count' => ArtshowItem::count()]))
                ->icon(ArtshowItemResource::getNavigationIcon())
                ->url(ArtshowItemResource::getUrl());

        if(Gate::check("viewAny", Dealer::class))
            $stats[] = Stat::make(__("Unconfirmed Dealer Registrations"), DealerResource::getNavigationBadge() ?? 0)
                ->description(__("Total Dealers: :count", ['count' => Dealer::count()]))
                ->icon(DealerResource::getNavigationIcon())
                ->url(DealerResource::getUrl());

        return $stats;
    }
}
