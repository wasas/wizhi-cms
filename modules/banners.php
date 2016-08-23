<?php

/**
 * @return string attachment images src
 */
function wizhi_cms_banner_image() {

	global $post;

	if ( is_page() ) {

		$post_id    = $post->ID;
		$banner_src = get_post_meta( $post_id, '_banner_image', true );

		// 如果当前页面没有, 获取父级页面图像
		if ( ! $banner_src ) {
			$post_id    = $post->post_parent;
			$banner_src = get_post_meta( $post_id, '_banner_image', true );
		}

	} elseif ( is_single() ) {

		// 获取文章类型分类法
		$post_type  = $post->post_type;
		$taxonomies = array_values( get_object_taxonomies( $post_type, 'objects' ) ); #associated array to index array
		$taxonomy   = $taxonomies[ 0 ]->name;

		// 获取第一个分类项目
		$terms   = get_the_terms( $post->ID, $taxonomy );
		$term_id = $terms[ 0 ]->term_id;

		$banner_src = get_term_meta( $term_id, '_banner_image', true );

	} elseif ( is_tax() || is_category() ) {

		$queried_object = get_queried_object();
		$term_id        = $queried_object->term_id;

		$banner_src = get_term_meta( $term_id, '_banner_image', true );

	} else {

		$banner_src = null;

	}

	if ( $banner_src ) {
		return $banner_src;
	} else {
		return '';
	}

}