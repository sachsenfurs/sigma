<?php

namespace App\Providers\Filament;

use App\Filament\Clusters\SigPlanning\Resources\TimetableEntryResource\Widgets\SigPlannerWidget;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Carbon;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(asset('images/logo.png'))
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
//                SigPlannerWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->bootUsing(function() use ($panel) {

                // this has to be inside the "bootUsing" function since we are using database queries
                // which are not always available when the AdminPanelProvider is registered!
                // ServiceProviders otherwise are registered in an early state of the application, even in artisan commands!

                $panel->plugin(
                    FilamentFullCalendarPlugin::make()
                      ->schedulerLicenseKey("CC-Attribution-NonCommercial-NoDerivatives")
                      ->selectable(true)
                      ->editable(true)
                      ->plugins([
                          'resourceTimeGrid',
                          'resourceTimeline',
                      ])
                      ->config([
                          'initialView' => "resourceTimeGridDay",
                          'resources' => SigLocation::select("id", "name AS title")->where("show_default", true)->get()->toArray(),
                          'headerToolbar' => [
                              'left' => 'prev,next,today',
                              'center' => 'title',
                              'right' => 'resourceTimeGridDay,resourceTimeline,dayGridMonth'
                          ],
                          'titleFormat' => [
                              'day' => 'numeric',
                              'month' => 'long',
                              'weekday' => 'long',
                          ],
                          'nowIndicator' => true,
                          'slotMinTime' => "08:00:00",
                          'slotMaxTime' => "28:00:00",
                          'eventResizableFromStart' => true,
                          'allDaySlot' => false,
                          'showNonCurrentDates' => true,
                          'defaultTimedEventDuration' => "01:00",
                          'forceEventDuration' => true,
                          'scrollTimeReset' => false,
                          'height' => '150vh',
                          'expandRows' => true,
                          'stickyHeaderDates' => true,
                          'contentHeight' => "auto",
                          'initialDate' => (function() {
                              $first = TimetableEntry::orderBy('start')->first();
                              if(Carbon::parse($first?->start)->isAfter(Carbon::now()))
                                  return $first->start->format("Y-m-d");
                              return Carbon::now()->format("Y-m-d");
                          })(),
                      ])
                );
            })

            ;
    }
}
