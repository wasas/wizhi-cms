<?php
/**
 * 主题辅助函数
 *
 */


if (! function_exists('dd')) {
	/**
	 * Dump the passed variables and end the script.
	 *
	 * @param  mixed
	 * @return void
	 */
	function dd(...$args)
	{
		foreach ($args as $x) {
			(new Dumper)->dump($x);
		}
		die(1);
	}
}

if (! function_exists('dda')) {
	/**
	 * Dump the passed array variables and end the script.
	 *
	 * @param  mixed
	 * @return void
	 */
	function dda(...$args)
	{
		foreach ($args as $x) {
			(new Dumper)->dump($x->toArray());
		}
		die(1);
	}
}


/**
 * 根据分类法获取文章类型
 *
 * @param string $taxonomy 分类法名称
 *
 * @return array
 */
function get_post_types_by_taxonomy( $taxonomy = 'category' ) {
	global $wp_taxonomies;

	return ( isset( $wp_taxonomies[ $taxonomy ] ) ) ? $wp_taxonomies[ $taxonomy ]->object_type : [];
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
 * 获取分类法列表模板, 排除默认的页面模板
 *
 * @package   helper
 * @return array 分类法列表模板
 */
function wizhi_get_taxonomy_templates() {

	$page_templates = wp_get_theme()->get_page_templates();

	$taxonomy_templates = [];

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
		$image_sizes        = [];

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
	$output             = [];
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
	$output              = [];
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
	$output           = [];
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