<?php

namespace App\Providers;

use App\Helper\Telegram\LoginWidget;
use App\Settings\AppSettings;
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
        // overwrite config and re-registering service provider
        try {
            config([
                'telegram.bots.default.name' => app(AppSettings::class)->telegram_bot_name,
                'services.telegram-bot-api.name' => app(AppSettings::class)->telegram_bot_name,
                'telegram.bots.default.token' => app(AppSettings::class)->telegram_bot_token,
                'services.telegram-bot-api.token' => app(AppSettings::class)->telegram_bot_token,
            ]);
            (new \NotificationChannels\Telegram\TelegramServiceProvider(app()))->register();
        } catch(\Exception $e) {
            // table don't exists. Probably because the migration is currently running where all service providers being invoked...
            // not the best solution atm
        }
    }
}
