<?php

namespace App\Providers;

use App\Integrations\Barq\GraphQLClient;
use App\Settings\AppSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class BarqServiceProvider extends ServiceProvider
{
    public function register(): void {
        $this->app->singleton(GraphQLClient::class, function() {

            $request = Http::acceptJson()
                ->asJson()
                ->timeout(10)
                ->withToken(app(AppSettings::class)->barq_jwt);

            return new GraphQLClient(
                $request,
                'https://api.barq.app/graphql'
            );
        });
    }
}
