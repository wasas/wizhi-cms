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

/**
 * 自动加载 PHP 文件
 *
 * @param $folder
 */
function include_all_php( $folder ) {
	foreach ( glob( "{$folder}/*.php" ) as $filename ) {
		require_once $filename;
	}
}

// 加载功能函数
include_all_php( WIZHI_CMS . 'inc/' );
include_all_php( WIZHI_CMS . 'inc/metabox' );

// 加载逻辑个设置代码
include_all_php( WIZHI_CMS . 'modules/' );
include_all_php( WIZHI_CMS . 'modules/metabox' );
include_all_php( WIZHI_CMS . 'modules/shortcodes' );