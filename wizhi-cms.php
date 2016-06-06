<?php
/*
Plugin Name:        Wizhi CMS
Plugin URI:         http://www.wpzhiku.com/wordpress-cms-plugin-wizhi-cms/
Description:        添加一些实用的功能，增加了一些效果类似dedecms(织梦)模板标签的一些简码。
Version:            1.4
Author:             Amos Lee
Author URI:         http://www.wpzhiku.com/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define( 'WIZHI_CMS', plugin_dir_path( __FILE__ ) );

// 快速添加文章类型和分类法
require_once( WIZHI_CMS . 'modules/post_types.php' );

require_once( WIZHI_CMS . 'inc/form-builder.php' );

require_once( WIZHI_CMS . 'inc/metabox/post.php' );
require_once( WIZHI_CMS . 'inc/metabox/term.php' );
require_once( WIZHI_CMS . 'inc/metabox/user.php' );
require_once( WIZHI_CMS . 'inc/metabox/option.php' );
require_once( WIZHI_CMS . 'inc/metabox/widget.php' );

require_once( WIZHI_CMS . 'inc/template-loader.php' );
require_once( WIZHI_CMS . 'inc/helper.php' );

// 添加页面分栏功能
require_once( WIZHI_CMS . 'builder/builder.php' );

// 显示分页
require_once( WIZHI_CMS . 'modules/pagination.php' );

// 常用简码
require_once( WIZHI_CMS . 'modules/shortcodes/loop.php' );
require_once( WIZHI_CMS . 'modules/shortcodes/element.php' );
require_once( WIZHI_CMS . 'modules/shortcodes-ui.php' );

// 过滤菜单和分类列表HTML
require_once( WIZHI_CMS . 'modules/walker.php' );

// 相关文章功能
require_once( WIZHI_CMS . 'modules/related.php' );

// 加速优化功能
require_once( WIZHI_CMS . 'modules/optimization.php' );

require_once( WIZHI_CMS . 'modules/content_types.php' );

require_once( WIZHI_CMS . 'example.php' );

// 插件设置
require_once( WIZHI_CMS . 'settings.php' );


// 获取插件设置值
$wizhi_use_cms_front = get_option( 'wizhi_use_cms_front' );

//加载 CSS 和 JS
if ( !is_admin() ) {
	add_action( 'wp_enqueue_scripts', 'wizhi_ui_scripts' );
	add_action( 'wp_enqueue_scripts', 'wizhi_ui_style' );

	add_action( 'admin_enqueue_scripts', 'wizhi_ui_scripts' );
	add_action( 'admin_enqueue_scripts', 'wizhi_ui_style' );
}

add_action( 'admin_enqueue_scripts', 'wizhi_ui_scripts' );

/**
 * 加载CSS
 *
 * @package front
 */
function wizhi_ui_style() {
	wp_register_style( 'wizhi-style', plugins_url( 'front/dist/styles/main.css', __FILE__ ) );
	wp_enqueue_style( 'wizhi-style' );
}

/**
 * 加载 JavaScript
 *
 * @package front
 */
function wizhi_ui_scripts() {
	wp_register_script( 'wizhi-script', plugins_url( 'front/dist/scripts/main.js', __FILE__ ), [ 'jquery' ], '1.1', true );
	wp_enqueue_script( 'wizhi-script' );
	wp_enqueue_script( "jquery-ui-tabs" );
}