<?php

namespace Wizhi\Services;

use Themosis\Facades\Route;
use Themosis\Foundation\ServiceProvider;

class RoutingService extends ServiceProvider {
	
	/**
	 * 注册插件路由
	 * 定义自定义命名空间
	 */
	public function register() {
		Route::group( [
			'namespace' => 'Wizhi\Controllers',
		], function () {
			require WIZHI_CMS_PATH . 'src/router.php';
		} );
	}

}

