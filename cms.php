<?php
/*
Plugin Name:        Wizhi CMS
Plugin URI:         http://www.wpzhiku.com/wordpress-cms-plugin-wizhi-cms/
Description:        添加一些实用的功能，增加了一些效果类似dedecms(织梦)模板标签的一些简码。
Version:            1.8
Author:             Amos Lee
Author URI:         http://www.wpzhiku.com/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define( 'WIZHI_CMS', plugin_dir_path( __FILE__ ) );
define( 'WIZHI_URL', plugins_url( '', __FILE__ ) );
defined( 'WIZHI_CMS_VERSION' ) or define( 'WIZHI_CMS_VERSION', '1.8' );

require_once( WIZHI_CMS . 'vendor/autoload.php' );

use Composer\Autoload\ClassLoader;
use Nette\Loaders\RobotLoader;

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
 * 加载翻译文件
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'wizhi', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	load_plugin_textdomain( 'fieldmanager', false, basename( dirname( __FILE__ ) ) . '/languages/' );

	// 检测是否安装了 fieldmanager 插件，如果未安装，包含插件内置的
	if ( ! function_exists( 'fieldmanager_load_class' ) ) {
		require_once WIZHI_CMS . 'inc/fieldmanager/fieldmanager.php';
	}
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


global $cms_settings;
$cms_settings = get_option( 'wizhi_cms_settings' );

/*
 * 自动加载
 */
$loader  = new ClassLoader();
$classes = [
	'Wizhi\\Helper\\'           => WIZHI_CMS . 'src/helpers/',
	'Wizhi\\Forms\\Controls\\'  => WIZHI_CMS . 'src/forms/controls',
	'Wizhi\\Forms\\Rendering\\' => WIZHI_CMS . 'src/forms/renders',
];

foreach ( $classes as $prefix => $path ) {
	$loader->addPsr4( $prefix, $path );
}

$loader->register();


// 自动加载目录中的类
$loader = new RobotLoader;
$loader->addDirectory( WIZHI_CMS . 'src' );

// And set caching to the 'temp' directory
$loader->setTempDirectory( WP_CONTENT_DIR . '/cache' );
$loader->register();

// 加载功能函数
include_all_php( WIZHI_CMS . 'inc/' );

// 加载逻辑设置代码
// todo: 改善自动加载性能
include_all_php( WIZHI_CMS . 'modules/' );
include_all_php( WIZHI_CMS . 'modules/metabox' );
include_all_php( WIZHI_CMS . 'modules/shortcodes' );

/*----------------------------------------------------*/
// WordPress database
/*----------------------------------------------------*/
$table_prefix = getenv( 'DB_PREFIX' ) ? getenv( 'DB_PREFIX' ) : 'wp_';
$collate      = defined( 'DB_COLLATE' ) && DB_COLLATE ? DB_COLLATE : 'utf8_general_ci';

/*----------------------------------------------------*/
// Illuminate database
/*----------------------------------------------------*/
$capsule = new Illuminate\Database\Capsule\Manager();
$capsule->addConnection( [
	'driver'    => 'mysql',
	'host'      => DB_HOST,
	'database'  => DB_NAME,
	'username'  => DB_USER,
	'password'  => DB_PASSWORD,
	'charset'   => DB_CHARSET,
	'collation' => $collate,
	'prefix'    => $table_prefix,
] );
$capsule->setAsGlobal();
$capsule->bootEloquent();
$GLOBALS[ 'themosis.capsule' ] = $capsule;

require_once WIZHI_CMS . 'framework/themosis.php';