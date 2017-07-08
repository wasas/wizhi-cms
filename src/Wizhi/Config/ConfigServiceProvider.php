<?php

namespace Wizhi\Config;

use Wizhi\Foundation\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('config.finder', function () {
            return new ConfigFinder();
        });

        $this->app->singleton('config.factory', function ($container) {
            return new ConfigFactory($container['config.finder']);
        });
    }
}
