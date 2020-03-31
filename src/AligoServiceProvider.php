<?php

namespace JinseokOh\Aligo;

use Illuminate\Support\ServiceProvider;

class AligoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/config/aligo.php', 'aligo');

        $this->app->singleton(AligoHandler::class, function ($app) {
            return new AligoHandler(new AligoClient());
        });

        $this->app->alias(AligoHandler::class, 'Aligo');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/aligo.php' => config_path('aligo.php'),
        ]);
    }
}
