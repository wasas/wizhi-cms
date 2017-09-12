<?php
/**
 * 插件辅助接口的路由
 */

use Themosis\Facades\Route;

// 辅助功能
Route::match( [ 'post' ], 'helper/upload/', 'HelperController@upload' );
Route::match( [ 'post' ], 'helper/get/sms/', 'HelperController@sms' );
Route::match( [ 'get' ], 'helper/get/captcha/{type}', 'HelperController@get_captcha' );
Route::match( [ 'post' ], 'helper/validate/sms/', 'HelperController@validate_sms' );
Route::match( [ 'post' ], 'helper/validate/username/', 'HelperController@validate_username' );
Route::match( [ 'post' ], 'helper/validate/email/', 'HelperController@validate_email' );

// 社交登录
Route::match( [ 'post' ], 'oauth/access/{provider}', 'OpenAuthController@access' );
Route::match( [ 'post' ], 'oauth/request/{provider}', 'OpenAuthController@request' );