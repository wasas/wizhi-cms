<?php

function additional_post_classes( $classes ) {
	global $wp_query;
	if( ( $wp_query->current_post + 1 ) % 4 == 0 ) {
		$classes[] = 'last';
	} else {
		$classes[] = 'post-odd';
	}
	return $classes;
}
add_filter( 'post_class', 'additional_post_classes' );