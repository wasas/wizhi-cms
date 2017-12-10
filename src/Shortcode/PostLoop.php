<?php
/**
 * Wizhi Shortcode
 * Wizhi CMS 插件使用的简码
 */

namespace Wizhi\Shortcode;

use Wizhi\Helper\Template;
use Wizhi\Helper\Pagination;

class PostLoop {

	/**
	 * 根据自定义分类显示文章, Loop 就应该是单纯接简单的文章循环, 更复杂的模块使用 List 或 Media 实现
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [loop type="post" tax="category" tag="default" num="6" pager="0" tmp="list"]
	 */
	public static function render( $atts ) {

		$default = [
			'type'  => 'post',
			'tax'   => 'category',
			'tag'   => 'default',
			'num'   => 8,
			'pager' => false,
			'tmp'   => 'lists',
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

		if ( $pager ) {
			$no_found_rows = false;
		} else {
			$no_found_rows = true;
		}

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		// 构建文章查询数组
		$args = [
			'post_type'           => $type,
			'orderby'             => 'post_date',
			'order'               => 'DESC',
			'posts_per_page'      => $num,
			'paged'               => $paged,
			'tax_query'           => $tax_query,
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => $no_found_rows,
		];

		// 输出
		$wizhi_query = new \WP_Query( $args );

		while ( $wizhi_query->have_posts() ) : $wizhi_query->the_post();
			echo Template::load_part( 'content', $tmp );
		endwhile;

		if ( $pager ) {
			Pagination::bootstrap( $wizhi_query );
		}

		wp_reset_postdata();
		wp_reset_query();

	}

}