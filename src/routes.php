<?php

use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;
use Themosis\Facades\Route;
use Themosis\Facades\View;

// 辅助功能
Route::match( [ 'post' ], 'helper/upload/', 'Helper@upload' );
Route::match( [ 'post' ], 'helper/get/sms/', 'Helper@sms' );
Route::match( [ 'get' ], 'helper/get/captcha/{type}', 'Helper@get_captcha' );
Route::match( [ 'post' ], 'helper/validate/sms/', 'Helper@validate_sms' );
Route::match( [ 'post' ], 'helper/validate/username/', 'Helper@validate_username' );
Route::match( [ 'post' ], 'helper/validate/email/', 'Helper@validate_email' );


Route::match( [ 'get', 'post' ], 'affiliate-login-page', 'Account@login' );
Route::match( [ 'get', 'post' ], 'affiliate-activation/{user_id?}/{activation_code?}', 'Account@activation' );

Route::get( 'affiliate-area-page', 'Dashboard@index' );
Route::get( 'affiliate-area-page/affiliate', 'Dashboard@affiliate' );
Route::get( 'affiliate-area-page/income', 'Dashboard@income' );
Route::get( 'affiliate-area-page/withdrawal', 'Dashboard@withdrawal' );

// 各个页面使用的菜单
$factory = new MenuFactory();
$menu    = $factory->createItem( 'Dashboard' );
$menu->addChild( '仪表盘', [ 'uri' => home_url( 'affiliate-area-page' ) ] );
$menu->addChild( '我的推荐', [ 'uri' => home_url( 'affiliate-area-page/affiliate' ) ] );
$menu->addChild( '收益记录', [ 'uri' => home_url( 'affiliate-area-page/income' ) ] );
$menu->addChild( '提现记录', [ 'uri' => home_url( 'affiliate-area-page/withdrawal' ) ] );

// 判断当前菜单
$actual_link = ( isset( $_SERVER[ 'HTTPS' ] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

foreach ( $menu as $m ) {

	if ( $m->getUri() . '/' == $actual_link ) {
		$m->setCurrent( true );
	}
}

$renderer = new ListRenderer( new \Knp\Menu\Matcher\Matcher() );
$menu     = $renderer->render( $menu );

View::share( 'menu', $menu );