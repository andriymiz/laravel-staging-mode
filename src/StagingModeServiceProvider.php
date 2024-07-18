<?php

namespace StagingMode;

use Illuminate\Support\ServiceProvider;

class StagingModeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/staging-mode.php' => config_path('staging-mode.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $packageConfigFile = __DIR__.'/../config/staging-mode.php';

        $this->mergeConfigFrom(
            $packageConfigFile, 'staging-mode'
        );
    }
}
