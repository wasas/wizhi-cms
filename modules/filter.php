<?php
/**
 * 过滤 WordPress 默认的一些输出和功能
 *
 * 为避免冲突，尽量用匿名函数
 */

/**
 * 过滤固定链接, 添加自定义链接选项
 *
 * @return mixed
 */
add_filter( 'the_permalink', function () {
	global $post;

	$post_id = $post->ID;

	$link = get_post_meta( $post_id, '_link_url', true );

	if ( empty( $link ) ) {
		$link = get_permalink();
	}

	return $link;
} );


/**
 * 添加语言类到 body 标签上
 *
 * @return array
 */
add_filter( 'body_class', function ( $classes ) {

	$lang      = get_bloginfo( 'language' );
	$classes[] = 'lang-' . $lang;

	return $classes;
} );


/**
 * 自动切换手机主题
 */
add_filter( 'template', 'wizhi_auto_switch_theme' );
add_filter( 'stylesheet', 'wizhi_auto_switch_theme' );
function wizhi_auto_switch_theme( $theme ) {

	$mobile_theme = get_option( 'wizhi_cms_settings' )[ 'mobile_theme' ];

	if ( isset( $mobile_theme ) && wp_is_mobile() ) {
		$theme = $mobile_theme;
	}

	return $theme;
}
