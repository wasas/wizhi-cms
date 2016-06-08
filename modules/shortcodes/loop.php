<?php
/**
 * Wizhi Shortcode
 * Wizhi CMS 插件使用的简码
 */


if ( ! function_exists( 'wizhi_shortcode_loop' ) ) {

	/**
	 * 根据自定义分类显示文章
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [wizhi_loop type="home" tax="home_tag" tag="yxdt" num="6" tmp="content" offset="0"]
	 */
	function wizhi_shortcode_loop( $atts ) {

		$default = [
			'type'   => 'post',
			'tax'    => 'category',
			'tag'    => 'default',
			'tmp'     => 'lists',
			'offset' => 0,
			'num'    => 8, // 数量: 显示文章数量，-1为全部显示
		];

		extract( shortcode_atts( $default, $atts ) );

		// 判断是否查询分类
		if ( empty( $tax ) ) {
			$tax_query = '';
		} else {
			$tax_query = [
				[
					'taxonomy' => $tax,
					'field'    => 'slug',
					'terms'    => $tag,
				],
			];
		}

		// 构建文章查询数组
		$args = [
			'post_type'      => $type,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'posts_per_page' => $num,
			'offset'         => $offset,
			'tax_query'      => $tax_query,
		];

		// 输出
		$the_query = new WP_Query( $args );

		while ( $the_query->have_posts() ) : $the_query->the_post();
			echo wizhi_load_template_part( 'content', $tmp );
		endwhile;

		wp_reset_postdata();
		wp_reset_query();

	}
}

add_shortcode( 'loop', 'wizhi_shortcode_loop' );