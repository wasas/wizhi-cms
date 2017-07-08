<?php

namespace Wizhi\Page;

use Wizhi\Foundation\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('page', function ($container) {

            $data = new PageData();

            $view = $container['view'];
            $view = $view->make('pages._wizhiCorePage');

            return new PageBuilder($data, $view, $container['validation'], $container['action']);
        });
    }
}
