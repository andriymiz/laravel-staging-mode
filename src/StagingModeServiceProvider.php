<?php

namespace StagingMode;

use Illuminate\Support\ServiceProvider;
use StagingMode\Console\Commands\StagingEnableCommand;

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
            __DIR__.'/../config/staging-mode.php' => $this->app->configPath('staging-mode.php'),
        ], 'staging-mode-config');

        if ($this->app->runningInConsole()) {
            $this->commands([StagingEnableCommand::class]);
        }
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
