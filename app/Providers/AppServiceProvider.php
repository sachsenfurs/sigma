<?php

namespace App\Providers;

use App\Services\Translator;
use App\Settings\AppSettings;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        // registering in boot because we are using values from settings (database)
        $this->app->singleton(Translator::class, function() {
            return new Translator(app(AppSettings::class)->deepl_api_key, app(AppSettings::class)->deepl_source_lang, app(AppSettings::class)->deepl_target_lang);
        });

    }
}
