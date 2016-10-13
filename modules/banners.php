<?php

/**
 * @return string attachment images src
 */
function wizhi_cms_banner_image( $show = "false" ) {

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

	} elseif ( is_post_type_archive() ) {

		// 文章类型存档
		$queried_object = get_queried_object();
		$banner_src = get_archive_option( $queried_object->name )[ 'banner' ];

	} elseif ( is_tax() || is_category() ) {

		// 分类方法或默认的分类
		$queried_object = get_queried_object();
		$term_id        = $queried_object->term_id;

		$banner_src = get_term_meta( $term_id, '_banner_image', true );

        // 分类方法的 banner 不存在，则用文章类型的
        if( ! $banner_src ){
            $post_type          = get_post_types_by_taxonomy( $queried_object->taxonomy );
            $post_type_settings = get_archive_option( $post_type[ 0 ] );

            $banner_src = $post_type_settings[ 'banner' ];
        }

	} else {

		// 默认为网站 Banner
		$banner_src = get_option( 'wizhi_cms_settings' )['banner'];

	}

	if( $show ){
		if ( $banner_src ) {
			return wp_get_attachment_url( $banner_src );
		} else {
			return '';
		}
	} else {
		if ( $banner_src ) {
			return $banner_src;
		} else {
			return '';
		}
	}

}