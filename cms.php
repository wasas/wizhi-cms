<?php
/*
Plugin Name:        Wizhi CMS
Plugin URI:         http://www.wpzhiku.com/wordpress-cms-plugin-wizhi-cms/
Description:        添加一些实用的功能，增加了一些效果类似dedecms(织梦)模板标签的一些简码。
Version:            1.8.3
Author:             Amos Lee
Author URI:         http://www.wpzhiku.com/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define( 'WIZHI_CMS_PATH', plugin_dir_path( __FILE__ ) );
define( 'WIZHI_CMS_URL', plugins_url( '', __FILE__ ) );
defined( 'WIZHI_CMS_VERSION' ) or define( 'WIZHI_CMS_VERSION', '1.8.0' );

require_once( WIZHI_CMS_PATH . 'vendor/autoload.php' );
require_once( WIZHI_CMS_PATH . 'src/database.php' );

// 插件加载时，加载翻译文件和 field manager 类
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'wizhi', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	load_plugin_textdomain( 'fieldmanager', false, basename( dirname( __FILE__ ) ) . '/languages/' );

	// 检测是否安装了 fieldmanager 插件，如果未安装，包含插件内置的
	if ( ! function_exists( 'fieldmanager_load_class' ) ) {
		require_once WIZHI_CMS_PATH . '/fieldmanager/fieldmanager.php';
	}
} );

register_activation_hook( __FILE__, 'wizhi_install_database' );

require_once WIZHI_CMS_PATH . 'framework/themosis.php';
require_once WIZHI_CMS_PATH . 'src/Bootstrap.php';


/*
 * 在插件中初始化框架
 *
 */

/*
 * 设置插件路径
 */
// $paths[ 'plugin.wizhi' ]           = WIZHI_CMS_PATH;
// $paths[ 'plugin.wizhi.resources' ] = WIZHI_CMS_PATH . 'templates/';
// $paths[ 'plugin.wizhi.storage' ]   = WIZHI_CMS_PATH . 'templates/cache';
//
// themosis_set_paths( $paths );

/*
 * 设置插件配置文件
 */
// container( 'config.finder' )->addPaths( [ WIZHI_CMS_PATH . 'src/config/', ] );

/*
 * 注册插件视图
 */
// container( 'view.finder' )->addLocation( WIZHI_CMS_PATH . 'templates/views' );

/*
 * 服务提供者
 */
// $providers = container( 'config.factory' )->get( 'providers' );
//
// foreach ( $providers as $provider ) {
// 	container()->register( $provider );
// }