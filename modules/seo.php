<?php

/**
 * 在不同类型的页面显示不同的封面图
 *
 * @return string attachment images src
 */
add_action( 'wp_head', 'wizhi_cms_seo', 0 );
function wizhi_cms_seo() {

	global $post;
	global $wp_locale;

	$m               = get_query_var( 'm' );
	$year            = get_query_var( 'year' );
	$monthnum        = get_query_var( 'monthnum' );
	$day             = get_query_var( 'day' );
	$search          = get_query_var( 's' );
	$seo_title       = '';
	$seo_description = '';

	$t_sep = '_';

	// 首页
	if ( is_home() || is_front_page() ) {

		$seo_title       = get_option( 'wizhi_cms_settings' )[ 'seo_title' ];
		$seo_description = get_option( 'wizhi_cms_settings' )[ 'seo_description' ];

		if ( ! $seo_title ) {
			$seo_title = get_bloginfo( 'name' );
		}

		if ( ! $seo_description ) {
			$seo_description = get_bloginfo( 'description' );
		}

	}

	// 单页面
	if ( is_singular() ) {

		// 单篇文章时, 描述优先级为 自定义字段 > 摘要 > 自动截取正文前面120个字符。

		$seo_title       = get_post_meta( get_the_id(), '_seo_title', true );
		$seo_description = get_post_meta( get_the_id(), '_seo_description', true );

		if ( ! $seo_title ) {
			$seo_title = single_post_title( '', false );;
		}

		if ( ! $seo_description ) {
			$seo_description = get_the_excerpt();
		}

		if ( ! $seo_description ) {
			$seo_description = mb_strimwidth( strip_tags( apply_filters( 'the_content', $post->post_content ) ), 0, 120, "……" );
		}

	}


	// 自定义文章类型存档
	if ( is_post_type_archive() ) {

		// 文章类型存档
		$post_type = get_query_var( 'post_type' );

		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}

		$seo_title       = get_archive_option( $post_type )[ 'seo_title' ];
		$seo_description = get_archive_option( $post_type )[ 'seo_description' ];

		if ( ! $seo_title ) {
			$seo_title = strip_tags( get_archive_option( $post_type )[ 'title' ] );
		}

		if ( ! $seo_title ) {
			$seo_title = post_type_archive_title( '', false );
		}

		if ( ! $seo_description ) {
			$seo_description = strip_tags( get_archive_option( $post_type )[ 'description' ] );
		}

	}


	// 分类法或文章类型存档, 描述优先级为 自定义字段 > 分类法项目描述
	if ( is_tax() || is_category() || is_tag() ) {

		$queried_object = get_queried_object();
		$term_id        = $queried_object->term_id;

		$seo_title       = get_term_meta( $term_id, '_seo_title', true );
		$seo_description = get_term_meta( $term_id, '_seo_description', true );

		if ( ! $seo_title ) {
			$seo_title = strip_tags( $queried_object->name );
		}

		if ( ! $seo_description ) {
			$seo_description = strip_tags( term_description( $term_id, $queried_object->taxonomy ) );
		}

	}


	// 作者存档
	if ( is_author() && ! is_post_type_archive() ) {

		$author_id = get_query_var( 'author' );

		$seo_title       = get_the_author_meta( 'display_name', $author_id );
		$seo_description = get_the_author_meta( 'description', $author_id );

		if ( ! $seo_title ) {
			$seo_description = get_the_author_meta( '_seo_title', $author_id );
		}

		if ( ! $seo_description ) {
			$seo_description = get_the_author_meta( '_seo_description', $author_id );
		}

	}

	// 月份存档
	if ( is_archive() && ! empty( $m ) ) {
		$my_year   = substr( $m, 0, 4 );
		$my_month  = $wp_locale->get_month( substr( $m, 4, 2 ) );
		$my_day    = intval( substr( $m, 6, 2 ) );
		$seo_title = $my_year . ( $my_month ? $t_sep . $my_month : '' ) . ( $my_day ? $t_sep . $my_day : '' );
	}


	//  年份存档
	if ( is_archive() && ! empty( $year ) ) {
		$seo_title = $year;
		if ( ! empty( $monthnum ) ) {
			$seo_title .= $t_sep . $wp_locale->get_month( $monthnum );
		}
		if ( ! empty( $day ) ) {
			$seo_title .= $t_sep . zeroise( $day, 2 );
		}
	}

	// 搜索结果页面
	if ( is_search() ) {
		$seo_title = sprintf( __( 'Search Results %1$s %2$s' ), strip_tags( $search ) );
	}

	// 404 页面
	if ( is_404() ) {
		$seo_title = __( 'Page not found' );
	}

	if ( $seo_title ) {
		echo '<title>' . trim( $seo_title ) . '</title>' . "\n";
	} else {
		echo wp_title( '_', false );
	}

	if ( $seo_description ) {
		echo '<meta name="description" content="' . trim( $seo_description ) . '"/>' . "\n";
	}

}