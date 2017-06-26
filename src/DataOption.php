<?php
/**
 * 输出数据数组
 */
class DataOption {


	/**
	 * 获取注册的文章类型数组
	 *
	 * @return array 文章类型数组
	 */
	public static function types() {

		$args_type = [
			'public'   => true,
			'_builtin' => false,
		];

		$post_types = get_post_types( $args_type, 'objects' );

		$output = [
			0      => sprintf( '— %s —', __( 'Select Content Type', 'wizhi' ) ),
			'post' => __( 'Post', 'wizhi' ),
			'page' => __( 'Page', 'wizhi' ),
		];

		foreach ( $post_types as $post_type ) {
			$output[ $post_type->name ] = $post_type->label;
		}

		return $output;
	}


	/**
	 * 获取注册的分类方法数组
	 *
	 * @return array 分类方法数组
	 */
	public static function taxonomies() {

		$output = [
			0          => sprintf( '— %s —', __( 'Select Taxonomy', 'wizhi' ) ),
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
			if ( ( ! $tax->show_tagcloud || empty( $tax->labels->name ) ) ) {
				continue;
			}
			$output[ esc_attr( $taxonomy ) ] = esc_attr( $tax->labels->name );
		}

		return $output;
	}


	/**
	 * 获取自定义分类法项目列表
	 *
	 * @param string $taxonomy 分类法名称
	 * @param int    $parent   父级分类项目 id
	 *
	 * @return array 分类法中的分类项目列表
	 */
	public static function terms( $taxonomy = 'post_tag', $parent = 0 ) {
		$terms = get_terms( $taxonomy, [
			'parent'     => $parent,
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


	/**
	 * 获取文章类型中的所有文章数组
	 *
	 * @param string $type 自定义文章类型别名
	 *
	 * @return array 文章列表数组, ID 为键, 标题为值
	 */
	public static function posts( $type = "post" ) {
		$args = [
			'post_type'      => $type,
			'posts_per_page' => '-1',
		];
		$loop = new WP_Query( $args );

		$output = [
			0 => sprintf( '— %s —', __( 'Select Content', 'wizhi' ) ),
		];

		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				$output[ get_the_ID() ] = get_the_title();
			endwhile;
		}

		wp_reset_postdata();

		return $output;
	}


	/**
	 * 排序方法
	 */
	public static function orders() {
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
	public static function colors() {
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
	public static function dirs() {
		$output           = [];
		$output[ 'ASC' ]  = __( 'ASC', 'wizhi' );
		$output[ 'DESC' ] = __( 'DESC', 'wizhi' );

		return $output;
	}


	/**
	 * 获取所有缩略图尺寸
	 *
	 * @return array $image_sizes 缩略图尺寸列表
	 */
	function sizes() {
		$image_sizes_orig   = get_intermediate_image_sizes();
		$image_sizes_orig[] = 'full';
		$image_sizes        = [];

		foreach ( $image_sizes_orig as $size ) {
			$image_sizes[ $size ] = $size;
		}

		return $image_sizes;
	}

}