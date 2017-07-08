<?php

namespace Wizhi\Metabox;

use Wizhi\Foundation\ServiceProvider;

class MetaboxServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('metabox', function ($container) {

            $data = new MetaboxData();

            $view = $container['view'];
            $view = $view->make('_wizhiCoreMetabox');

            $user = $container['user'];
            $user = $user->current();

            return new MetaboxBuilder($data, $view, $container['validation'], $user, $container['action'], $container['filter']);
        });
    }
}
