<?php
/**
 * Template Loader for Plugins.
 *
 * @package   Gamajo_Template_Loader
 * @author    Gary Jones
 * @link      http://github.com/GaryJones/Gamajo-Template-Loader
 * @copyright 2013 Gary Jones
 * @license   GPL-2.0+
 * @version   1.1.0
 */

/**
 * 自定义模板加载器, 优先加载主题中的模板, 如果主题中的模板不存在, 就加载插件中的
 *
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 */
function wz_get_template_part( $slug, $name = '' ) {
	$template = '';

	// 先查找主题中指定的模板yourtheme/slug-name.php 和 yourtheme/template-parts/slug-name.php
	if ( $name ) {
		$template = locate_template( array( "{$slug}-{$name}.php", "template-parts/{$slug}-{$name}.php" ) );
	}

	// 如果主题中的模板不存在, 获取插件中指定的模板 slug-name.php
	if ( ! $template && $name && file_exists( wizhi_plugin_path() . "/{$slug}-{$name}.php" ) ) {
		$template = wizhi_plugin_path() . "/{$slug}-{$name}.php";
	}

	// 如果模板文件还不存在, 获取主题中默认的模板, 查找 yourtheme/slug.php 和 yourtheme/template-parts/slug.php
	if ( ! $template ) {
		$template = locate_template( array( "{$slug}.php", wizhi_plugin_path() . "{$slug}.php" ) );
	}
	
	// 允许第三方插件过滤模板文件
	$template = apply_filters( 'wz_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}