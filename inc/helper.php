<?php

/**
 * 加载 wp_editor 需要的脚本, 设置项同 wp_editor
 *
 * @param array $settings
 */
function wizhi_wp_editor( $settings = [ ] ) {
	if ( ! class_exists( '_WP_Editors' ) ) {
		require( ABSPATH . WPINC . '/class-wp-editor.php' );
	}
	$set = _WP_Editors::parse_settings( 'wid', $settings );

	if ( ! current_user_can( 'upload_files' ) ) {
		$set[ 'media_buttons' ] = false;
	}

	if ( $set[ 'media_buttons' ] ) {
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'media-upload' );

		$post = get_post();
		if ( ! $post && ! empty( $GLOBALS[ 'post_ID' ] ) ) {
			$post = $GLOBALS[ 'post_ID' ];
		}

		wp_enqueue_media( [
			'post' => $post,
		] );
	}

	_WP_Editors::editor_settings( 'wid', $set );

	$wizhi_vars = [
		'url'          => get_home_url(),
		'cms_url'      => plugins_url(),
		'includes_url' => includes_url(),
	];

	wp_localize_script( 'jquery', 'wizhi_vars', $wizhi_vars );
}


/**
 * 初始化后加载 wp_editor
 */
add_action( 'init', 'wizhi_load_editor' );
function wizhi_load_editor() {
	$settings = [
		'editor_css' => 'typo wizhi',
	];

	wizhi_wp_editor( $settings );
}


/**
 * 获取当前分类的父级类 ID
 *
 * @param int    $term_id  分类 id
 * @param string $taxonomy 分类法名称
 *
 * @return mixed
 */
function wizhi_get_term_root_id( $term_id, $taxonomy ) {
	$this_term = get_term( $term_id, $taxonomy );

	while ( $this_term->parent ) {
		$this_term = get_term( $this_term->parent, $taxonomy );
	}

	return $this_term->term_id;
}


add_filter( 'the_permalink', 'filter_permalink' );
/**
 * 过滤固定链接, 添加自定义链接选项
 *
 * @return mixed
 */
function filter_permalink() {
	global $post;

	$post_id = $post->ID;

	$link = get_post_meta( $post_id, '_link_url', true );

	if ( empty( $link ) ) {
		$link = get_permalink();
	}

	return $link;
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
		$title = sprintf( __( '%s' ), get_the_date( _x( 'Y', 'wizhi', 'yearly archives date format' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( '%s' ), get_the_date( _x( 'F Y', 'wizhi', 'monthly archives date format' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( '%s' ), get_the_date( _x( 'F j, Y', 'wizhi', 'daily archives date format' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'wizhi', 'post format archive title' );
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
		$title = __( 'Archives', 'wizhi' );
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


if ( ! function_exists( 'wizhi_get_image_sizes' ) ) {

	/**
	 * 获取所有缩略图尺寸
	 *
	 * @return array $image_sizes 缩略图尺寸列表
	 */
	function wizhi_get_image_sizes() {
		$image_sizes_orig   = get_intermediate_image_sizes();
		$image_sizes_orig[] = 'full';
		$image_sizes        = [ ];

		foreach ( $image_sizes_orig as $size ) {
			$image_sizes[ $size ] = $size;
		}

		return $image_sizes;
	}

}


/**
 * 排序方法
 */
function wizhi_get_display_order() {
	$output             = [ ];
	$output[ 'author' ] = __( 'Author', 'wizhi' );
	$output[ 'date' ]   = __( 'Date', 'wizhi' );
	$output[ 'title' ]  = __( 'Title', 'wizhi' );
	$output[ 'rand' ]   = __( 'Random', 'wizhi' );

	return $output;
}


/**
 * 排序方法
 */
function wizhi_color_option() {
	$output              = [ ];
	$output[]            = __( 'Default', 'wizhi' );
	$output[ 'success' ] = __( 'Success（Green）', 'wizhi' );
	$output[ 'info' ]    = __( 'Info（Blue）', 'wizhi' );
	$output[ 'warning' ] = __( 'Warning（Orange）', 'wizhi' );
	$output[ 'danger' ]  = __( 'Danger（Red）', 'wizhi' );

	return $output;
}


/**
 * 排序依据
 */
function wizhi_get_display_direction() {
	$output           = [ ];
	$output[ 'ASC' ]  = __( 'ASC', 'wizhi' );
	$output[ 'DESC' ] = __( 'DESC', 'wizhi' );

	return $output;
}


/**
 * 获取注册的文章类型
 *
 * @return array $post_types  文章类型列表
 */
if ( ! function_exists( 'wizhi_get_post_types' ) ) {

	function wizhi_get_post_types() {

		$args_type = [
			'public'   => true,
			'_builtin' => false,
		];

		$post_types = get_post_types( $args_type, 'objects' );

		$output = [
			0      => sprintf( '— %s —', __( 'Select content type', 'wizhi' ) ),
			'post' => __( 'Post', 'wizhi' ),
			'page' => __( 'Page', 'wizhi' ),
		];

		foreach ( $post_types as $post_type ) {
			$output[ $post_type->name ] = $post_type->label;
		}

		return $output;
	}

}


/**
 * 获取文章类型中的所有文章数组
 *
 * @param string $type 自定义文章类型别名
 * @param string $id   文章 ID
 *
 * @return array 文章列表数组, ID 为键, 标题为值
 */
function wizhi_get_post_list( $type = "post", $id = "false" ) {
	$args = [
		'post_type'      => $type,
		'posts_per_page' => '-1',
	];
	$loop = new WP_Query( $args );

	$output = [
		0 => sprintf( '— %s —', __( 'Select content', 'wizhi' ) ),
	];

	if ( $loop->have_posts() ) {
		while ( $loop->have_posts() ) : $loop->the_post();
			$fieldout = get_the_title();
			if ( $id != "false" ) {
				$fieldout .= " (" . get_the_ID() . ")";
			}
			$output[ get_the_ID() ] = $fieldout;
		endwhile;
	}
	wp_reset_postdata();

	return $output;
}


/**
 * 获取自定义分类法列表
 *
 * @param string $type
 *
 * @return array
 */
function wizhi_get_taxonomy_list( $type = "taxonomy" ) {
	$output = [
		0          => sprintf( '— %s —', __( 'Select taxonomy', 'wizhi' ) ),
		'category' => __( 'Category', 'wizhi' ),
		'post_tag' => __( 'Tags', 'wizhi' ),
	];

	$args = [
		'public'   => true,
		'_builtin' => false,
	];

	$taxonomies = get_taxonomies( $args );

	foreach ( $taxonomies as $taxonomy ) {
		$tax = get_taxonomy( $taxonomy );
		if ( ( ! $tax->show_tagcloud || empty( $tax->labels->name ) ) && $type == "tag" ) {
			continue;
		}
		$output[ esc_attr( $taxonomy ) ] = esc_attr( $tax->labels->name );
	}

	return $output;
}


/**
 * 获取注册的分类方法
 *
 * @return array $post_types  文章类型列表
 */
if ( ! function_exists( 'wizhi_get_taxonomies' ) ) {

	function wizhi_get_taxonomies() {

		$args = [
			'public'   => true,
			'_builtin' => false,
		];

		$taxonomies = get_taxonomies( $args );

		$output = [ 'category', 'post_tag' ];

		foreach ( $taxonomies as $taxonomy ) {
			$output[] = $taxonomy;
		}

		return $output;
	}

}


/**
 * 获取自定义分类法项目列表
 *
 * @param string $taxonomy 分类法名称
 *
 * @return array
 */
function wizhi_get_term_list( $taxonomy = 'post_tag' ) {
	$terms = get_terms( $taxonomy, [
		'parent'     => 0,
		'hide_empty' => false,
	] );

	$output = [
		0 => sprintf( '— %s —', __( 'Select Category', 'wizhi' ) ),
	];

	if ( is_wp_error( $terms ) ) {
		return $output;
	}

	foreach ( $terms as $term ) {

		$output[ $term->term_id ] = $term->name;
		$term_children            = get_term_children( $term->term_id, $taxonomy );

		if ( is_wp_error( $term_children ) ) {
			continue;
		}

		foreach ( $term_children as $term_child_id ) {

			$term_child = get_term_by( 'id', $term_child_id, $taxonomy );

			if ( is_wp_error( $term_child ) ) {
				continue;
			}

			$output[ $term_child->term_id ] = $term_child->name;
		}

	}

	return $output;
}