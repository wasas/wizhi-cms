<?php

/**
 * 获取当前分类的父级类 ID
 *
 * @param int $cat 分类 id
 * @param string $taxonomy 分类法名称
 *
 * @return mixed
 */
function get_term_root_id( $term_id, $taxonomy ) {
	$this_term = get_term( $term_id, $taxonomy );
	
	while ( $this_term->parent ) {
		$this_term = get_term( $this_term->parent, $taxonomy );
	}

	return $this_term->term_id;
}

/**
 * 给当前链接加 “active” 类
 *
 * @param $var   string 查询参数
 * @param $value string 当前连接的查询值
 *
 * @package conditions
 *
 * @return mixed string|bool 如果是当前链接, 返回“active” 字符串, 如果不是,返回 false
 */
function is_current_link( $var, $value, $default ) {
	$query_value = isset( $_GET[ $var ] ) ? $_GET[ $var ] : $default;

	if ( $query_value == $value ) {
		return "active";
	}

	return false;
}


/**
 *  获取存档或文章标题作为页面标题使用
 *
 * @package   template
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
 * 获取分类法列表模板, 排除默认的页面模板
 *
 * @package   helper
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
 * @package   helper
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
 * @param  $slug string 分类id
 *
 * @package   helper
 *
 * @return string 反格式化后的字符串
 */
function wizhi_unslug( $slug = null ) {

	if ( ! $slug ) {
		$post_data = get_post( get_the_id(), ARRAY_A );
		$slug      = $post_data[ 'post_name' ];
	}

	return ucwords( str_replace( "-", " ", $slug ) );
}


if ( ! function_exists( "order_no" ) ) {
	/**
	 * 生成订单号
	 *
	 * @package   helper
	 *
	 * @return string 订单号字符串
	 */
	function order_no() {
		return date( 'Ymd' ) . str_pad( mt_rand( 1, 99999 ), 5, '0', STR_PAD_LEFT );
	}
}