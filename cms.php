<?php
/*
Plugin Name:        Wizhi CMS
Plugin URI:         http://www.wpzhiku.com/wordpress-cms-plugin-wizhi-cms/
Description:        添加一些实用的功能，增加了一些效果类似dedecms(织梦)模板标签的一些简码。
Version:            1.6
Author:             Amos Lee
Author URI:         http://www.wpzhiku.com/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define( 'WIZHI_CMS', plugin_dir_path( __FILE__ ) );
defined( 'WIZHI_CMS_VERSION' ) or define( 'WIZHI_CMS_VERSION', '1.6' );

require_once( WIZHI_CMS . 'vendor/autoload.php' );

if ( version_compare( phpversion(), '5.6.0', '<' ) ) {
	function wizhi_cms_phpold() {
		printf( '<div class="error"><p>' . __( '您当前的PHP版本（%1$s）不符合插件要求, 请升级到 PHP %2$s 或更新的版本， 否则插件没有任何作用。', 'wizhi' ) . '</p></div>', phpversion(), '5.6.0' );
	}

	// 显示警告信息
	if ( is_admin() ) {
		add_action( 'admin_notices', 'wizhi_cms_phpold' );
	}

	return;
}

/**
 * Load translations
 */
add_action( 'plugins_loaded', 'wizhi_cms_load_textdomain' );
function wizhi_cms_load_textdomain() {
	load_plugin_textdomain( 'wizhi', false, basename( dirname( __FILE__ ) ) . '/lang/' );
}

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

// 加载 RedBean PHP
if ( ! class_exists( 'R' ) ) {
	require_once WIZHI_CMS . 'inc/rb.php';
}

// 加载功能函数
include_all_php( WIZHI_CMS . 'inc/' );
include_all_php( WIZHI_CMS . 'inc/crud' );
include_all_php( WIZHI_CMS . 'inc/controls' );
include_all_php( WIZHI_CMS . 'inc/metabox' );

// 加载逻辑个设置代码
include_all_php( WIZHI_CMS . 'modules/' );
include_all_php( WIZHI_CMS . 'modules/metabox' );
include_all_php( WIZHI_CMS . 'modules/shortcodes' );
include_all_php( WIZHI_CMS . 'modules/app' );

$is_enable_builder = get_option( 'is_enable_builder' );

if ( $is_enable_builder ) {
	// include_all_php( WIZHI_CMS . 'builder' );
}

//$args2 = [
//	'rows_per_page' => 20,
//	'column_names'  => [
//		'umeta_id'   => '主键',
//		'user_id'    => '用户 ID',
//		'meta_key'   => '键名',
//		'meta_value' => '键值',
//	],
//	'excluded_columns' => [  ],
//];
//
//new CrudController( 'wp_usermeta', 'wizhi_post2', $args2 );

require_once WIZHI_CMS . 'example.php';