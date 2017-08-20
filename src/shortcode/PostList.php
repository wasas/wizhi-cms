<?php
/**
 * Wizhi Shortcode
 * Wizhi CMS 插件使用的简码
 */

namespace Wizhi\Shortcode;

use Wizhi\helper\Template;

class PostList {

	/**
	 * 显示文章标题列表
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [list type="post" tax="category" tag="default" num="6" cut="26" heading="false" pager="0" tmp="list"]
	 */
	public static function render( $atts ) {

		$default = [
			'type'    => 'post',
			'tax'     => 'category',
			'tag'     => 'default',
			'num'     => 8,
			'heading' => true,
			'pager'   => false,
			'tmp'     => 'list',
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

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		if ( $pager ) {
			$no_found_rows = false;
		} else {
			$no_found_rows = true;
		}

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

		// get term archive name and link
		$cat = get_term_by( 'slug', $tag, $tax );

		if ( $cat ) {
			$cat_name = $cat->name;
			$cat_link = get_term_link( $tag, $tax );
		}

		// 输出
		$wizhi_query = new WP_Query( $args );

		if ( $heading == false || empty( $tax ) ) {
			echo '<div class="sep ui-list-' . trim( $type ) . '-' . trim( $tag ) . '">';
			echo '<ul class="ui-list">';

			while ( $wizhi_query->have_posts() ) : $wizhi_query->the_post();
				echo Template::load_part( 'content', $tmp );
			endwhile;

			echo '</ul>';
			echo '</div>';
		} else {
			echo '<div class="panel panel-default ' . trim( $type ) . '-' . trim( $tag ) . '">';
			echo '<div class="panel-heading">';
			echo '<h3 class="panel-title"><a href="' . $cat_link . '">' . $cat_name . '</a></h3>';
			echo '<a class="panel-more" href="' . $cat_link . '" target="_blank">更多></a>';
			echo '</div>';
			echo '<div class="panel-body">';
			echo '<div class="panel-content"><ul class="ui-list ui-list-' . $tag . '">';

			while ( $wizhi_query->have_posts() ) : $wizhi_query->the_post();
				echo Template::load_part( 'content', $tmp );
			endwhile;

			echo '</ul></div></div></div>';
		}

		if ( $pager ) {
			wizhi_pagination( $wizhi_query );
		}

		wp_reset_postdata();
		wp_reset_query();

	}
}