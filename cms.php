<?php
/*
Plugin Name:        Wizhi CMS
Plugin URI:         http://www.wpzhiku.com/wordpress-cms-plugin-wizhi-cms/
Description:        添加一些实用的功能，增加了一些效果类似dedecms(织梦)模板标签的一些简码。
Version:            1.8.0
Author:             Amos Lee
Author URI:         http://www.wpzhiku.com/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define( 'WIZHI_CMS', plugin_dir_path( __FILE__ ) );
define( 'WIZHI_URL', plugins_url( '', __FILE__ ) );
defined( 'WIZHI_CMS_VERSION' ) or define( 'WIZHI_CMS_VERSION', '1.8.0' );

require_once( WIZHI_CMS . 'vendor/autoload.php' );

use Composer\Autoload\ClassLoader;

if ( version_compare( phpversion(), '5.6.0', '<' ) ) {

	// 显示警告信息
	if ( is_admin() ) {
		add_action( 'admin_notices', function () {
			printf( '<div class="error"><p>' . __( 'Your PHP version（%1$s）can`t match plugin require, please update to PHP %2$s or later.', 'wizhi' ) . '</p></div>', phpversion(), '5.6' );
		} );
	}

	return;
}


/**
 * 加载后台样式
 */
add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_style( 'wizhi-form-style', WIZHI_URL . '/front/dist/styles/admin.css' );
	wp_enqueue_script( 'wizhi-form-scripts', WIZHI_URL . '/front/dist/scripts/admin.js', [ 'jquery' ], WIZHI_CMS_VERSION, true );
} );


/**
 * 加载翻译文件
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'wizhi', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	load_plugin_textdomain( 'fieldmanager', false, basename( dirname( __FILE__ ) ) . '/languages/' );

	// 检测是否安装了 fieldmanager 插件，如果未安装，包含插件内置的
	if ( ! function_exists( 'fieldmanager_load_class' ) ) {
		require_once WIZHI_CMS . '/fieldmanager/fieldmanager.php';
	}
} );

// 设置插件设置到全局变量
global $wizhi_option;
$wizhi_option = get_option( 'wizhi_cms_settings' );

/*
 * 自动加载
 */
$loader  = new ClassLoader();
$classes = [
	'Wizhi\\Helper\\'           => WIZHI_CMS . 'src/helpers/',
	'Wizhi\\Option\\'           => WIZHI_CMS . 'src/option/',
	'Wizhi\\Walker\\'           => WIZHI_CMS . 'src/walker/',
	'Wizhi\\Metabox\\'          => WIZHI_CMS . 'src/metabox/',
	'Wizhi\\Action\\'           => WIZHI_CMS . 'src/action/',
	'Wizhi\\Shortcode\\'        => WIZHI_CMS . 'src/shortcode/',
	'Wizhi\\Forms\\Controls\\'  => WIZHI_CMS . 'src/forms/controls',
	'Wizhi\\Forms\\Rendering\\' => WIZHI_CMS . 'src/forms/renders',
];

foreach ( $classes as $prefix => $path ) {
	$loader->addPsr4( $prefix, $path );
}

$loader->register();

require_once WIZHI_CMS . 'framework/themosis.php';
require_once WIZHI_CMS . 'src/Bootstrap.php';