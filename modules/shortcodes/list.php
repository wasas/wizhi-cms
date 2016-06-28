<?php
if ( ! function_exists( 'wizhi_shortcode_list' ) ) {

	/**
	 * 显示文章标题列表
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [list type="post" tax="category" tag="default" num="6" cut="26" heading="false" pager="0" tmp="list"]
	 */
	function wizhi_shortcode_list( $atts ) {

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
			'post_type'      => $type,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'posts_per_page' => $num,
			'paged'          => $paged,
			'tax_query'      => $tax_query,
			'no_found_rows'  => $no_found_rows,
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
			echo '<div class="sep ui-list-' . $type . $tag . '">';
			echo '<ul class="ui-list">';

			while ( $wizhi_query->have_posts() ) : $wizhi_query->the_post();
				echo wizhi_load_template_part( 'content', $tmp );
			endwhile;

			echo '</ul>';
			echo '</div>';
		} else {
			echo '<div class="ui-box ' . $type . $tag . '">';
			echo '<div class="ui-box-head">';
			echo '<h3 class="ui-box-head-title"><a href="' . $cat_link . '">' . $cat_name . '</a></h3>';
			echo '<a class="ui-box-head-more" href="' . $cat_link . '" target="_blank">更多></a>';
			echo '</div>';
			echo '<div class="ui-box-content"><ul class="ui-list ui-list-' . $tag . '">';

			while ( $wizhi_query->have_posts() ) : $wizhi_query->the_post();
				echo wizhi_load_template_part( 'content', $tmp );
			endwhile;

			echo '</ul></div></div>';
		}

		if ( $pager ) {
			wizhi_pagination( $wizhi_query );
		}

		wp_reset_postdata();
		wp_reset_query();

	}
}
add_shortcode( 'list', 'wizhi_shortcode_list' );