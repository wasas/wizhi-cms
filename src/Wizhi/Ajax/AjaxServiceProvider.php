<?php

namespace Wizhi\Ajax;

use Wizhi\Foundation\ServiceProvider;

class AjaxServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ajax', function($container)
        {
            return new AjaxBuilder($container['action']);
        });
    }
}
