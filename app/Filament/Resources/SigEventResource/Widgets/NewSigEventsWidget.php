<?php

namespace App\Filament\Resources\SigEventResource\Widgets;

use App\Enums\Approval;
use App\Filament\Resources\Ddas\ArtshowItemResource;
use App\Filament\Resources\Ddas\DealerResource;
use App\Filament\Resources\SigEventResource;
use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\Dealer;
use App\Models\SigEvent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewSigEventsWidget extends BaseWidget
{
    protected function getStats(): array {
        return [
            Stat::make(__('Unconfirmed SIGs'), SigEventResource::getNavigationBadge())
                ->description(__("Total Events: :count", ['count' => SigEvent::count()]))
                ->icon(SigEventResource::getNavigationIcon())
                ->url(SigEventResource::getUrl()),
            Stat::make(__("Unconfirmed Dealer Registrations"), DealerResource::getNavigationBadge())
                ->description(__("Total Dealers: :count", ['count' => Dealer::count()]))
                ->icon(DealerResource::getNavigationIcon())
                ->url(DealerResource::getUrl()),
            Stat::make(__("Unconfirmed Art Show Items"), ArtshowItemResource::getNavigationBadge())
                ->description(__("Total Art Show Items: :count", ['count' => ArtshowItem::count()]))
                ->icon(ArtshowItemResource::getNavigationIcon())
                ->url(ArtshowItemResource::getUrl()),

        ];
    }
}
