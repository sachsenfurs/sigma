<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\DashboardWidget;
use App\Http\Middleware\SetLocale;
use App\Settings\AppSettings;
use Filament\Forms\Components\DateTimePicker;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Infolists\Infolist;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Table;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // Default pagination for all tables
        Table::configureUsing(function(Table $table): void {
            $table->defaultPaginationPageOption(25);
        });
        Table::$defaultDateTimeDisplayFormat = "l, d.m.Y - H:i";
        Table::$defaultDateDisplayFormat = "l, d.m.Y";
        Table::$defaultCurrency = config("app.currency");

        Infolist::$defaultDateTimeDisplayFormat = Table::$defaultDateTimeDisplayFormat;
        Infolist::$defaultDateDisplayFormat     = Table::$defaultDateDisplayFormat;

        DateTimePicker::configureUsing(function(DateTimePicker $datePicker) {
            $datePicker->displayFormat(Table::$defaultDateTimeDisplayFormat);
        });

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::hex("#d47f2f"),
            ])
            ->brandLogo(app(AppSettings::class)->logoUrl())
            ->favicon(asset('images/favicon.png'))
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                DashboardWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                SetLocale::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
//            ->breadcrumbs(false)
            ->userMenuItems([
                MenuItem::make()
                        ->label(__("Leave Admin Interface"))
                        ->url(fn (): string => "/")
                        ->icon('heroicon-m-cog-8-tooth'),
            ])
            ->maxContentWidth(MaxWidth::Full)
            ;
    }
}
