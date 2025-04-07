<?php

namespace Celcredit;

use Celcredit\Celcredit;
use Illuminate\Support\ServiceProvider;

class CelcreditServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('celcredit.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'celcredit');

        // Register the card management class to use with the facade
        $this->app->singleton('celcredit', function () {
            return new Celcredit();
        });
    }
}
