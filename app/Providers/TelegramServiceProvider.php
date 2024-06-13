<?php

namespace App\Providers;

use App\Helper\Telegram\LoginWidget;
use App\Settings\AppSettings;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Telegram\Telegram;
use NotificationChannels\Telegram\TelegramChannel;
use Spatie\LaravelSettings\Settings;

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
                'services.telegram-bot-api.name' => app(AppSettings::class)->telegram_bot_name,
                'services.telegram-bot-api.token' => app(AppSettings::class)->telegram_bot_token,
            ]);
            (new \NotificationChannels\Telegram\TelegramServiceProvider(app()))->register();
        } catch(\Exception $e) {
            // table don't exists. Probably because the migration is currently running where all service providers being invoked...
            // not the best solution atm
        }
    }
}
