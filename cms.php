<?php
/*
Plugin Name:        Wizhi CMS
Plugin URI:         http://www.wpzhiku.com/wordpress-cms-plugin-wizhi-cms/
Description:        添加一些实用的功能，增加了一些效果类似dedecms(织梦)模板标签的一些简码。
Version:            1.7.8
Author:             Amos Lee
Author URI:         http://www.wpzhiku.com/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define( 'WIZHI_CMS', plugin_dir_path( __FILE__ ) );
define( 'WIZHI_URL', plugins_url( '', __FILE__ ) );
defined( 'WIZHI_CMS_VERSION' ) or define( 'WIZHI_CMS_VERSION', '1.6' );

require_once( WIZHI_CMS . 'vendor/autoload.php' );

if ( version_compare( phpversion(), '5.6.0', '<' ) ) {

	// 显示警告信息
	if ( is_admin() ) {
		add_action( 'admin_notices', function () {
			printf( '<div class="error"><p>' . __( 'Your PHP version（%1$s）can`t match plugin require, please update to PHP %2$s or later.', 'wizhi' ) . '</p></div>', phpversion(), '5.6.0' );
		} );
	}

	return;
}

/**
 * 加载翻译文件
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'wizhi', false, basename( dirname( __FILE__ ) ) . '/lang/' );
	load_plugin_textdomain( 'fieldmanager', false, basename( dirname( __FILE__ ) ) . '/lang/' );
} );

/**
 * 自动加载 PHP 文件
 *
 * @param  string $folder 需要被包含的文件夹路径, 不能自动发现子目录, 每个子目录需要单独定义
 */
function include_all_php( $folder ) {
	foreach ( glob( "{$folder}/*.php" ) as $filename ) {
		require_once $filename;
	}
}

/**
 * 插件启用时,检测 fieldmanager 和 shortcode ui 插件是否激活, 如果未激活, 包含插件内置的 fieldmanager
 */
add_action( 'plugins_loaded', 'load_fieldmanager', 0 );
function load_fieldmanager() {
	if ( ! function_exists( 'fieldmanager_load_class' ) ) {
		require_once WIZHI_CMS . 'inc/fieldmanager/fieldmanager.php';
	}
}

// 加载 RedBean PHP
if ( ! class_exists( 'R' ) ) {
	require_once WIZHI_CMS . 'inc/rb.php';
}

global $cms_settings;

$cms_settings = get_option( 'wizhi_cms_settings' );

// 加载功能函数
include_all_php( WIZHI_CMS . 'inc/' );

// 加载逻辑设置代码
include_all_php( WIZHI_CMS . 'modules/' );
include_all_php( WIZHI_CMS . 'modules/metabox' );
include_all_php( WIZHI_CMS . 'modules/shortcodes' );
include_all_php( WIZHI_CMS . 'modules/app' );