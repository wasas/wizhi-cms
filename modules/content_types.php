<?php

/**
 * 添加默认的自定义文章类型和分类法
 */
function add_default_content_type() {

	$types              = wizhi_post_types();
	$icons              = wizhi_post_types_icon();
	$enabled_post_types = get_option( 'enabled_post_types', [ ] );

	// 添加默认的文章类型和分类方法
	foreach ( $enabled_post_types as $slug ) {
		wizhi_create_types( $slug, $types[ $slug ], [ 'title', 'editor', 'thumbnail' ], true, $icons[ $slug ] );
		wizhi_create_taxs( $slug . 'cat', $slug, $types[ $slug ] . __( 'Category', 'wizhi' ), true );
	}
}

add_action( 'init', 'add_default_content_type' );