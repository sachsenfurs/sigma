<?php

namespace App\Providers;

use App\Integrations\Lassie\LassieClient;
use App\Settings\AppSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class LassieServiceProvider extends ServiceProvider
{
    public function register(): void {
        $this->app->singleton(LassieClient::class, function() {
            return new LassieClient(
                Http::asForm()->timeout(10),
                'https://api.lassie.online/v2.0',
                app(AppSettings::class)->lassie_api_key,
            );
        });
    }
}
