<?php

/**
 *  获取存档或文章标题作为页面标题使用
 *
 * @return array
 */
function wizhi_get_the_archive_title() {
	if ( is_category() ) {
		$title = sprintf( __( '%s' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( '%s' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( '%s' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( '%s' ), get_the_date( _x( 'Y', 'yearly archives date format' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( '%s' ), get_the_date( _x( 'F Y', 'monthly archives date format' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( '%s' ), get_the_date( _x( 'F j, Y', 'daily archives date format' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( '%s' ), post_type_archive_title( '', false ) );
	} elseif ( is_page() ) {
		$title = sprintf( __( '%s' ), get_the_title( '', false ) );
	} elseif ( is_single() ) {
		$title = sprintf( __( '%s' ), get_the_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
	} else {
		$title = __( 'Archives' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @since 4.1.0
	 *
	 * @param string $title Archive title to be displayed.
	 */
	return apply_filters( 'get_the_archive_title', $title );
}


/**
 *  获取分类法列表模板, 排除默认的页面模板
 *
 * @return array 分类法列表模板
 */
function wizhi_get_taxonomy_templates() {

	$page_templates = wp_get_theme()->get_page_templates();

	$taxonomy_templates = [ ];

	foreach ( $page_templates as $filename => $template_name ) {

		if ( strchr( $filename, 'taxonomy-parts' ) ) {
			$taxonomy_templates[ $filename ] = $template_name;
		}

	}

	return $taxonomy_templates;

}


/**
 * 判断文章是否包含在父级分类中
 *
 * @param int $cats  分类id
 * @param int $_post 文章id
 *
 * @return boolean
 */
if ( ! function_exists( 'post_is_in_descendant_category' ) ) {

	function post_is_in_descendant_category( $cats, $_post = null ) {
		foreach ( (array) $cats as $cat ) {
			// get_term_children() accepts integer ID only
			$descendants = get_term_children( (int) $cat, 'category' );
			if ( $descendants && in_category( $descendants, $_post ) ) {
				return true;
			}
		}

		return false;
	}

}


/**
 * 反向转换slug为正常的字符串
 *
 * @return [type] [description]
 */
function wizhi_unslug( $slug = null ) {

	if ( ! $slug ) {
		$post_data = get_post( get_the_id(), ARRAY_A );
		$slug      = $post_data[ 'post_name' ];
	}

	return ucwords( str_replace( "-", " ", $slug ) );
}


function wizhi_slug( $title = null ) {

	if ( ! $title ) {
		$post_data = get_post( get_the_id(), ARRAY_A );
		$slug      = $post_data[ 'post_title' ];
	}

	return str_replace( "_", " ", $slug );
}


/**
 * 获取缩略图的url
 *
 * @param boolean $post_id 文章id
 * @param string  $size    缩略图尺寸名称
 *
 * @return string 图片url
 */
if ( ! function_exists( 'wizhi_get_thumb_src' ) ) {

	function wizhi_get_thumb_src( $post_id = null, $size = 'post-thumbnail' ) {

		$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
		$url   = $thumb[ '0' ];

		return $url;
	}

}


/**
 * 获取自定义图片字段的url
 *
 * @param null   $post_id 文章ID
 * @param string $key     自定义字段key
 * @param string $size    缩略图的尺寸名称
 * @param bool   $single  是否为单张
 *
 * @return mixed
 */
if ( ! function_exists( 'wizhi_get_meta_src' ) ) {

	function wizhi_get_meta_src( $post_id = null, $key = '', $size = 'post-thumbnail', $single = true ) {

		$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

		$attachment_id  = get_post_meta( $post_id, $key, $single );
		$attachment_src = wp_get_attachment_image_src( $attachment_id, $size );

		return $attachment_src[ '0' ];
	}
}


/**
 * 获取文章或分类项目的自定义字段值
 *
 * @param string $key    meta_key
 * @param bool   $single return string or array
 *
 * @return mixed
 */
if ( ! function_exists( 'wizhi_get_meta' ) ) {

	function wizhi_get_meta( $key = '', $single = false ) {

		$pid = get_queried_object_id();

		if ( is_singular() ) {
			$value = get_post_meta( $pid, $key, $single );
		} else {
			$value = get_term_meta( $pid, $key, $single );
		}

		return $value;
	}

}


/**
 * 获取分类项目名称
 *
 * @since 2.5.0
 *
 * @param int    $id       Post ID.
 * @param string $taxonomy Taxonomy name.
 * @param string $before   Optional. Before list.
 * @param string $sep      Optional. Separate items using this.
 * @param string $after    Optional. After list.
 *
 * @return string|bool|WP_Error A list of terms on success, false if there are no terms, WP_Error on failure.
 */
if ( ! function_exists( 'wizhi_the_term_names' ) ) {

	function wizhi_the_term_names( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {

		$terms = get_the_terms( $id, $taxonomy );

		if ( is_wp_error( $terms ) ) {
			return $terms;
		}

		if ( empty( $terms ) ) {
			return false;
		}

		$links = [ ];

		foreach ( $terms as $term ) {
			$link = get_term_link( $term, $taxonomy );
			if ( is_wp_error( $link ) ) {
				return $link;
			}
			$links[] = $term->name;
		}

		/**
		 * Filter the term links for a given taxonomy.
		 *
		 * The dynamic portion of the filter name, `$taxonomy`, refers
		 * to the taxonomy slug.
		 *
		 * @since 2.5.0
		 *
		 * @param array $links An array of term links.
		 */
		$term_links = apply_filters( "term_links-$taxonomy", $links );

		return $before . join( $sep, $term_links ) . $after;

	}

}


// 生成订单号
if ( ! function_exists( "order_no" ) ) {
	function order_no() {
		return date( 'Ymd' ) . str_pad( mt_rand( 1, 99999 ), 5, '0', STR_PAD_LEFT );
	}
}