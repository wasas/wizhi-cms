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
