<?php

namespace App\Providers;

use App\Helper\Telegram\LoginWidget;
use Illuminate\Support\ServiceProvider;

class TelegramServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {
        $this->app->singleton(LoginWidget::class, fn() => new LoginWidget());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        //
    }
}
