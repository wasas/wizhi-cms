<?php

namespace Wizhi\Field;

use Wizhi\Foundation\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('field', function ($container) {
            return new FieldFactory($container['view']);
        });
    }
}
