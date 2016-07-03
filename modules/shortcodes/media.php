<?php
/**
 * Wizhi Shortcode
 * Wizhi CMS 插件使用的简码
 */

if ( ! function_exists( 'wizhi_shortcode_media' ) ) {
	/**
	 * 图文混排样式简码
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [media type="post" tax="category" tag="default" num="6" heading="false", pager="0" tmp="list"]
	 */
	function wizhi_shortcode_media( $atts ) {

		$default = [
			'type'    => 'post',
			'tax'     => 'category',
			'tag'     => 'default',
			'num'     => '4',
			'heading' => true,
			'pager'   => false,
			'tmp'     => 'media',
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

		// 根据分类别名获取分类ID
		$args = [
			'post_type'      => $type,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'posts_per_page' => $num,
			'paged'          => $paged,
			'tax_query'      => $tax_query,
			'ignore_sticky_posts' => 1,
			'no_found_rows'  => $no_found_rows,
		];

		$cat = get_term_by( 'slug', $tag, $tax );

		if ( $cat ) {
			$cat_name = $cat->name;
			$cat_link = get_term_link( $tag, $tax );
		}

		// 输出
		$wizhi_query = new WP_Query( $args );

		if ( $heading == false || empty( $tax ) ) {

			echo '<div class="sep medias media-' . $type . $tag . '">';
			while ( $wizhi_query->have_posts() ) : $wizhi_query->the_post();
				echo wizhi_load_template_part( 'content', $tmp );
			endwhile;
			echo '</div>';

		} else {

			echo '<div class="sep ui-box ' . $type . $tag . '">';
			echo '<div class="ui-box-head">';
			echo '<h3 class="ui-box-head-title"><a href="' . $cat_link . '">' . $cat_name . '</a></h3>';
			echo '<a class="ui-box-head-more" href="' . $cat_link . '" target="_blank">更多></a>';
			echo '</div>';
			echo '<div class="ui-box-content">';

			echo '<div class="medias media-' . $tag . '">';

				while ( $wizhi_query->have_posts() ) : $wizhi_query->the_post();
					echo wizhi_load_template_part( 'content', $tmp );
				endwhile;

			echo '</div>';

			echo '</div>';
			echo '</div>';

		}

		if ( $pager ) {
			wizhi_pagination( $wizhi_query );
		}

		wp_reset_postdata();
		wp_reset_query();

	}
}
add_shortcode( 'media', 'wizhi_shortcode_media' );