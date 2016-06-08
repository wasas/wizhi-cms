<?php

if ( ! function_exists( 'wizhi_shortcode_content' ) ) {
	/**
	 * 输出标题文章列表时实现，默认带标题
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [page_cont id="1" count="200" thumbs="thumbnail" more="true"]
	 */
	function wizhi_shortcode_content( $atts ) {
		$default = [
			'id'     => 1,
			'cont'   => 200,
			'thumbs' => 'thumbnail',
			'more'   => false,
		];
		extract( shortcode_atts( $default, $atts ) );

		$page = get_post( $id );

		echo '<div class="sep">';

		// 输出
		echo '<a target="_blank" href="' . get_page_link( $id ) . '">';
		echo get_the_post_thumbnail( $id, $thumbs );
		echo '</a>';

		if ( $cont == 0 ) {
			echo $page->post_content;
		} else {
			echo wp_trim_words( $page->post_content, $cont, "..." );
		}

		if ( $more == true ) {
			echo '<a target="_blank" href="' . get_page_link( $id ) . '">更多>></a>';
		} else {
			echo '';
		}

		echo '</div>';

		wp_reset_postdata();
		wp_reset_query();
	}
}
add_shortcode( 'content', 'wizhi_shortcode_content' );