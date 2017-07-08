<?php

namespace Wizhi\PostType;

use Wizhi\Foundation\ServiceProvider;

class PostTypeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('posttype', function ($container) {

            $data = new PostTypeData();

            $view = $container['view'];
            $view = $view->make('_wizhiCorePublishBox');

            return new PostTypeBuilder($container, $data, $container['metabox'], $view, $container['action'], $container['filter']);
        });
    }
}
