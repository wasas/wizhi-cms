<?php

namespace Wizhi\Asset;

use Wizhi\Foundation\ServiceProvider;

class AssetServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('asset.finder', function () {
            return new AssetFinder();
        });
        $this->app->singleton('asset', function ($container) {
            return new AssetFactory($container['asset.finder'], $container);
        });
    }
}
