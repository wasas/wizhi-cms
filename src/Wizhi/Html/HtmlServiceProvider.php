<?php

namespace Wizhi\Html;

use Wizhi\Foundation\ServiceProvider;

class HtmlServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('html', 'Wizhi\Html\HtmlBuilder');
    }
}
