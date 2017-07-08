<?php
/**
 * 主题辅助函数
 *
 */

use Wizhi\Foundation\Application;

if ( ! function_exists( 'dd' ) ) {
	/**
	 * 输出传入的变量并结束程序
	 *
	 * @param  mixed
	 *
	 * @return void
	 */
	function dd( ...$args ) {
		foreach ( $args as $x ) {
			( new Dumper )->dump( $x );
		}
		die( 1 );
	}
}

if ( ! function_exists( 'dda' ) ) {
	/**
	 * 输出传入的变量并结束程序
	 *
	 * @param  mixed
	 *
	 * @return void
	 */
	function dda( ...$args ) {
		foreach ( $args as $x ) {
			( new Dumper )->dump( $x->toArray() );
		}
		die( 1 );
	}
}


/**
 * 根据分类法获取文章类型
 *
 * @param string $taxonomy 分类法名称
 *
 * @return array
 */
if ( ! function_exists( 'get_post_types_by_taxonomy' ) ) {
	function get_post_types_by_taxonomy( $taxonomy = 'category' ) {
		global $wp_taxonomies;

		return ( isset( $wp_taxonomies[ $taxonomy ] ) ) ? $wp_taxonomies[ $taxonomy ]->object_type : [];
	}
}

/**
 * 获取当前分类的父级类 ID
 *
 * @param int    $term_id  分类 id
 * @param string $taxonomy 分类法名称
 *
 * @return mixed
 */
if ( ! function_exists( 'wizhi_get_term_root_id' ) ) {
	function wizhi_get_term_root_id( $term_id, $taxonomy ) {
		$this_term = get_term( $term_id, $taxonomy );

		while ( $this_term->parent ) {
			$this_term = get_term( $this_term->parent, $taxonomy );
		}

		return $this_term->term_id;
	}
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
if ( ! function_exists( 'is_current_link' ) ) {
	function is_current_link( $var, $value, $default ) {
		$query_value = isset( $_GET[ $var ] ) ? $_GET[ $var ] : $default;

		if ( $query_value == $value ) {
			return "active";
		}

		return false;
	}
}


/**
 * 获取分类法列表模板, 排除默认的页面模板
 *
 * @package   helper
 * @return array 分类法列表模板
 */
if ( ! function_exists( 'wizhi_get_taxonomy_templates' ) ) {
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
if ( ! function_exists( "wizhi_unslug" ) ) {
	function wizhi_unslug( $slug = null ) {

		if ( ! $slug ) {
			$post_data = get_post( get_the_id(), ARRAY_A );
			$slug      = $post_data[ 'post_name' ];
		}

		return ucwords( str_replace( "-", " ", $slug ) );
	}
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

if ( ! function_exists( 'wizhi_is_subpage' ) ) {
	/**
	 * 判断当前页面是否为子页面
	 *
	 * @param array $parent 父级页面
	 *
	 * @return int|bool 子页面的父级页面ID，如果不是子页面，返回 false
	 */
	function wizhi_is_subpage( array $parent ) {
		global $post;

		$parentPage = get_post( $post->post_parent );

		if ( is_page() && $post->post_parent && $parentPage->post_name === $parent[ 0 ] ) {
			return $post->post_parent;
		}

		return false;
	}
}

if ( ! function_exists( 'wizhi_convert_path' ) ) {
	/**
	 * 转换 '.' 到 '/' 目录分隔
	 *
	 * @param string $path 以 '.' 分隔的初始目录
	 *
	 * @return string 转换后的已 '/' 分隔的目录
	 */
	function wizhi_convert_path( $path ) {
		if ( strpos( $path, '.' ) !== false ) {
			$path = str_replace( '.', DS, $path );
		} else {
			$path = trim( $path );
		}

		return (string) $path;
	}
}

if ( ! function_exists( 'td' ) ) {
	/**
	 * 打印并结束值 - 用来调试
	 *
	 * @param mixed $value Any PHP value.
	 */
	function td( $value ) {
		$attributes = func_get_args();
		if ( count( $attributes ) == 1 ) {
			$attributes = $attributes[ 0 ];
		}
		echo '<pre>';
		print_r( $attributes );
		echo '</pre>';
		wp_die();
	}
}

if ( ! function_exists( 'tp' ) ) {
	/**
	 * 打印值
	 *
	 * @param mixed $value Any PHP value
	 */
	function tp( $value ) {
		$attributes = func_get_args();
		if ( count( $attributes ) == 1 ) {
			$attributes = $attributes[ 0 ];
		}
		echo '<pre>';
		print_r( $attributes );
		echo '</pre>';
	}
}

if ( ! function_exists( 'wizhi_assets' ) ) {
	/**
	 * 返回应用前端资源 URL
	 *
	 * @return string
	 */
	function wizhi_assets() {
		// Check if the theme helper function exists.
		// Only if a wizhi-theme is used.
		if ( function_exists( 'wizhi_theme_assets' ) ) {
			return wizhi_theme_assets();
		}

		return get_template_directory_uri() . '/resources/assets';
	}
}

if ( ! function_exists( 'wizhi_get_the_query' ) ) {
	/**
	 * 返回 WP Query 值
	 *
	 * @return object The global WP_Query instance.
	 */
	function wizhi_get_the_query() {
		global $wp_query;

		return $wp_query;
	}
}

if ( ! function_exists( 'wizhi_use_permalink' ) ) {
	/**
	 * Conditional function that checks if WP
	 * is using a pretty permalink structure.
	 *
	 * @return bool True. False if not using permalink.
	 */
	function wizhi_use_permalink() {
		global $wp_rewrite;

		if ( ! $wp_rewrite->permalink_structure == '' ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'wizhi_add_filters' ) ) {
	/**
	 * Helper that runs multiple add_filter
	 * functions at once.
	 *
	 * @param array  $tags     Filter tags.
	 * @param string $function The name of the global function to call.
	 */
	function wizhi_add_filters( array $tags, $function ) {
		foreach ( $tags as $tag ) {
			add_filter( $tag, $function );
		}
	}
}

if ( ! function_exists( 'wizhi_get_post_id' ) ) {
	/**
	 * A function that retrieves the post ID during
	 * a wp-admin request on posts and custom post types.
	 *
	 * @return int|null
	 */
	function wizhi_get_post_id() {
		$id = null;

		// When viewing the cpt (GET)
		if ( isset( $_GET[ 'post' ] ) ) {
			$id = $_GET[ 'post' ];
		}

		// When saving the cpt (POST)
		if ( isset( $_POST[ 'post_ID' ] ) ) {
			$id = $_POST[ 'post_ID' ];
		}

		return $id;
	}
}

if ( ! function_exists( 'wizhi_is_post' ) ) {
	/**
	 * A function that checks you're on a specified
	 * admin page, post, or custom post type (edit) in order to display
	 * a certain content.
	 *
	 * Example : Place a specific metabox for a page, a post or a one of your
	 * custom post type.
	 *
	 * Give the post ID. Visible in the admin uri in your browser.
	 *
	 * @param int $id A WP_Post ID
	 *
	 * @return bool True. False if not a WordPress post type.
	 */
	function wizhi_is_post( $id ) {
		$postId = wizhi_get_post_id();

		if ( ! is_null( $postId ) && is_numeric( $id ) && $id === (int) $postId ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'wizhi_attachment_id_from_url' ) ) {
	/**
	 * A function that returns the 'attachment_id' of a
	 * media file by giving its URL.
	 *
	 * @param string $url The media/image URL - Works only for images uploaded from within WordPress.
	 *
	 * @return int|bool The image/attachment_id if it exists, false if not.
	 */
	function wizhi_attachment_id_from_url( $url = null ) {
		/*-----------------------------------------------------------------------*/
		// Load the DB class
		/*-----------------------------------------------------------------------*/
		global $wpdb;

		/*-----------------------------------------------------------------------*/
		// Set attachment_id
		/*-----------------------------------------------------------------------*/
		$id = false;

		/*-----------------------------------------------------------------------*/
		// If there is no url, return.
		/*-----------------------------------------------------------------------*/
		if ( null === $url ) {
			return;
		}

		/*-----------------------------------------------------------------------*/
		// Get the upload directory paths
		/*-----------------------------------------------------------------------*/
		$upload_dir_paths = wp_upload_dir();

		/*-----------------------------------------------------------------------*/
		// Make sure the upload path base directory exists in the attachment URL,
		// to verify that we're working with a media library image
		/*-----------------------------------------------------------------------*/
		if ( false !== strpos( $url, $upload_dir_paths[ 'baseurl' ] ) ) {
			/*-----------------------------------------------------------------------*/
			// If this is the URL of an auto-generated thumbnail,
			// get the URL of the original image
			/*-----------------------------------------------------------------------*/
			$url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $url );

			/*-----------------------------------------------------------------------*/
			// Remove the upload path base directory from the attachment URL
			/*-----------------------------------------------------------------------*/
			$url = str_replace( $upload_dir_paths[ 'baseurl' ] . '/', '', $url );

			/*-----------------------------------------------------------------------*/
			// Grab the database prefix
			/*-----------------------------------------------------------------------*/
			$prefix = $wpdb->prefix;

			/*-----------------------------------------------------------------------*/
			// Finally, run a custom database query to get the attachment ID
			// from the modified attachment URL
			/*-----------------------------------------------------------------------*/
			$id = $wpdb->get_var( $wpdb->prepare( "SELECT {$prefix}posts.ID FROM $wpdb->posts {$prefix}posts, $wpdb->postmeta {$prefix}postmeta WHERE {$prefix}posts.ID = {$prefix}postmeta.post_id AND {$prefix}postmeta.meta_key = '_wp_attached_file' AND {$prefix}postmeta.meta_value = '%s' AND {$prefix}posts.post_type = 'attachment'", $url ) );
		}

		return $id;
	}
}

if ( ! function_exists( 'wizhi_is_template' ) ) {
	/**
	 * A function that checks if we are using a page template.
	 *
	 * @param array $name Template properties.
	 *
	 * @return bool True: use of a template. False: no template.
	 */
	function wizhi_is_template( array $name = [] ) {
		$queriedObject = get_queried_object();

		if ( is_a( $queriedObject, 'WP_Post' ) && 'page' === $queriedObject->post_type ) {
			// Sanitized value
			$template = Meta::get( $queriedObject->ID, '_wizhiPageTemplate' );

			// If no template selected, just return;
			if ( $template === 'none' ) {
				return false;
			}

			// If template...
			if ( isset( $template ) && ! empty( $template ) ) {
				/*-----------------------------------------------------------------------*/
				// If the page template name is defined within the routes array, handle
				// the template
				/*-----------------------------------------------------------------------*/
				if ( in_array( $template, $name ) ) {
					return true;
				}
			}

			return false;
		}
	}
}

if ( ! function_exists( 'e' ) ) {
	/**
	 * Escape HTML entities in a string.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	function e( $value ) {
		return htmlentities( $value, ENT_QUOTES, 'UTF-8', false );
	}
}

if ( ! function_exists( 'starts_with' ) ) {
	/**
	 * Determine if a given string starts with a given substring.
	 *
	 * @param string       $haystack
	 * @param string|array $needles
	 *
	 * @return bool
	 */
	function starts_with( $haystack, $needles ) {
		foreach ( (array) $needles as $needle ) {
			if ( $needle != '' && strpos( $haystack, $needle ) === 0 ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'array_get' ) ) {
	/**
	 * Get an item from an array using "dot" notation.
	 *
	 * @param array  $array
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	function array_get( $array, $key, $default = null ) {
		if ( is_null( $key ) ) {
			return $array;
		}

		if ( isset( $array[ $key ] ) ) {
			return $array[ $key ];
		}

		foreach ( explode( '.', $key ) as $segment ) {
			if ( ! is_array( $array ) || ! array_key_exists( $segment, $array ) ) {
				return value( $default );
			}

			$array = $array[ $segment ];
		}

		return $array;
	}
}

if ( ! function_exists( 'array_set' ) ) {
	/**
	 * Set an array item to a given value using "dot" notation.
	 *
	 * If no key is given to the method, the entire array will be replaced.
	 *
	 * @param array  $array
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return array
	 */
	function array_set( &$array, $key, $value ) {
		if ( is_null( $key ) ) {
			return $array = $value;
		}

		$keys = explode( '.', $key );

		while ( count( $keys ) > 1 ) {
			$key = array_shift( $keys );

			// If the key doesn't exist at this depth, we will just create an empty array
			// to hold the next value, allowing us to create the arrays to hold final
			// values at the correct depth. Then we'll keep digging into the array.
			if ( ! isset( $array[ $key ] ) || ! is_array( $array[ $key ] ) ) {
				$array[ $key ] = [];
			}

			$array = &$array[ $key ];
		}

		$array[ array_shift( $keys ) ] = $value;

		return $array;
	}
}

if ( ! function_exists( 'array_except' ) ) {
	/**
	 * Get all of the given array except for a specified array of items.
	 *
	 * @param array $array
	 * @param array $keys
	 *
	 * @return array
	 */
	function array_except( $array, $keys ) {
		return array_diff_key( $array, array_flip( (array) $keys ) );
	}
}

if ( ! function_exists( 'array_is_sequential' ) ) {
	/**
	 * Check if an array is sequential (have keys from 0 to n) or not.
	 *
	 * @param array $array The array to check.
	 *
	 * @return bool
	 */
	function array_is_sequential( $array ) {
		return array_keys( $array ) === range( 0, count( $array ) - 1 );
	}
}

if ( ! function_exists( 'value' ) ) {
	/**
	 * Return the default value of the given value.
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	function value( $value ) {
		return $value instanceof Closure ? $value() : $value;
	}
}

if ( ! function_exists( 'with' ) ) {
	/**
	 * Return the given object. Useful for chaining.
	 *
	 * @param mixed $object
	 *
	 * @return mixed
	 */
	function with( $object ) {
		return $object;
	}
}

if ( ! function_exists( 'str_contains' ) ) {
	/**
	 * Determine if a given string contains a given substring.
	 *
	 * @param string       $haystack
	 * @param string|array $needles
	 *
	 * @return bool
	 */
	function str_contains( $haystack, $needles ) {
		foreach ( (array) $needles as $needle ) {
			if ( $needle != '' && strpos( $haystack, $needle ) !== false ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'app' ) ) {
	/**
	 * Helper function to quickly retrieve an instance.
	 *
	 * @param null  $abstract The abstract instance name.
	 * @param array $parameters
	 *
	 * @return mixed
	 */
	function app( $abstract = null, array $parameters = [] ) {
		if ( is_null( $abstract ) ) {
			return Application::getInstance();
		}

		return Application::getInstance()->make( $abstract, $parameters );
	}
}

if ( ! function_exists( 'container' ) ) {
	/**
	 * Helper function to quickly retrieve an instance.
	 *
	 * @param null  $abstract The abstract instance name.
	 * @param array $parameters
	 *
	 * @return mixed
	 */
	function container( $abstract = null, array $parameters = [] ) {
		return app( $abstract, $parameters );
	}
}

if ( ! function_exists( 'wizhi' ) ) {
	/**
	 * Helper function to retrieve the Wizhi class instance.
	 *
	 * @return Wizhi
	 */
	function wizhi() {
		if ( ! class_exists( 'Wizhi' ) ) {
			wp_die( 'Wizhi has not yet been initialized. Please make sure the Wizhi framework is installed.' );
		}

		return Wizhi::instance();
	}
}

if ( ! function_exists( 'view' ) ) {
	/**
	 * Helper function to build views.
	 *
	 * @param string $view The view relative path, name.
	 * @param array  $data Passed data.
	 * @param array  $mergeData
	 *
	 * @return string
	 */
	function view( $view = null, array $data = [], array $mergeData = [] ) {
		$factory = container( 'view' );

		if ( func_num_args() === 0 ) {
			return $factory;
		}

		return $factory->make( $view, $data, $mergeData )->render();
	}
}

if ( ! function_exists( 'meta' ) ) {
	/**
	 * Helper function to get any meta data from objects.
	 *
	 * @param string $key
	 * @param int    $id
	 * @param string $context
	 * @param bool   $single
	 *
	 * @return mixed|string
	 */
	function meta( $key = '', $id = null, $context = 'post', $single = true ) {
		if ( is_null( $id ) ) {
			$id = get_the_ID();
		}

		// If no ID found, return empty string.
		if ( ! $id ) {
			return '';
		}

		return get_metadata( $context, $id, $key, $single );
	}
}