<?php

namespace Wizhi\Html;

use Wizhi\Foundation\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('form', function ($container) {
            return new FormBuilder($container['html'], $container['request']);
        });
    }
}
