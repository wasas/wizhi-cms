<?php

namespace Wizhi\Route;

use Illuminate\Events\Dispatcher;
use Wizhi\Foundation\ServiceProvider;

class RouteServiceProvider extends ServiceProvider {
	public function register() {
		/*
		 * 注册事项调度器到容器
		 */
		$this->app->bind( 'events', function ( $container ) {
			return new Dispatcher( $container );
		} );
		$this->app->singleton( 'router', function ( $container ) {
			return new Router( $container[ 'events' ], $container );
		} );
	}
}
