<?php

namespace Wizhi\User;

use Wizhi\Foundation\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('user', function ($container) {

            $view = $container['view'];
            $view = $view->make('_wizhiUserCore');

            return new UserFactory($view, $container['validation'], $container['action']);
        });
    }
}
