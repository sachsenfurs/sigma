<?php

namespace App\Providers\Filament;

use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard;
use Filament\Support\Enums\Width;
use App\Filament\Widgets\DashboardWidget;
use App\Http\Middleware\SetLocale;
use App\Settings\AppSettings;
use Filament\Forms\Components\DateTimePicker;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
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
            $table->defaultPaginationPageOption(25)
                  ->paginationPageOptions([5, 10, 25, 50, 100]);
        });
        Table::configureUsing(fn(Table $table) => $table->defaultDateTimeDisplayFormat("l, d.m.Y - H:i"));
        Table::configureUsing(fn(Table $table) => $table->defaultDateDisplayFormat("l, d.m.Y"));
        Table::configureUsing(fn(Table $table) => $table->defaultCurrency(config("app.currency")));

        Schema::configureUsing(fn (Schema $schema) => $schema->defaultDateTimeDisplayFormat("l, d.m.Y - H:i"));
        Schema::configureUsing(fn (Schema $schema) => $schema->defaultDateDisplayFormat("l, d.m.Y"));

        DateTimePicker::configureUsing(fn(DateTimePicker $datePicker) => $datePicker->displayFormat("l, d.m.Y - H:i"));

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::generateV3Palette("#d47f2f"),
            ])
            ->brandLogo(app(AppSettings::class)->logoUrl())
            ->favicon(asset('images/favicon.png'))
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Dashboard::class,
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
                Action::make("leave")
                    ->label(__("Leave Admin Interface"))
                    ->url(fn (): string => "/")
                    ->icon('heroicon-m-cog-8-tooth'),
            ])
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ;
    }
}
