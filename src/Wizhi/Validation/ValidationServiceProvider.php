<?php

namespace Wizhi\Validation;

use Wizhi\Foundation\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('validation', function () {
            return new ValidationBuilder();
        });
    }
}
