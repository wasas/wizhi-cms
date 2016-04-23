<?php

/**
 * @param array $classes 文章CSS 类数组
 *
 * @return array 处理后的文章 CSS 类数组
 */
function additional_post_classes( $classes ) {
	global $wp_query;
	if ( ( $wp_query->current_post + 1 ) % 3 == 0 ) {
		$classes[] = 'col3-last';
	} elseif ( ( $wp_query->current_post + 1 ) % 4 == 0 ) {
		$classes[] = 'col4-last';
	} elseif ( ( $wp_query->current_post + 1 ) % 5 == 0 ) {
		$classes[] = 'col5-last';
	} else {
		$classes[] = '';
	}

	return $classes;
}

add_filter( 'post_class', 'additional_post_classes' );