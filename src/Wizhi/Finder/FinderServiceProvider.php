<?php

namespace Wizhi\Finder;

use Illuminate\Filesystem\Filesystem;
use Wizhi\Foundation\ServiceProvider;

class FinderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('filesystem', function () {
            return new Filesystem();
        });
    }
}
