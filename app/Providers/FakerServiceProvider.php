<?php

namespace App\Providers;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create(config("app.faker_locale"));
            $faker->addProvider(new FakerPicsumImagesProvider($faker));
            return $faker;
        });
    }

}
