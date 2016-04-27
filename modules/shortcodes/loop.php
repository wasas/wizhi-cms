<?php
/**
 * Wizhi Shortcode
 * Wizhi CMS 插件使用的简码
 */


if ( ! function_exists( 'wizhi_shortcode_page_cont' ) ) {
	/**
	 * 输出标题文章列表时实现，默认带标题
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [page_cont id="1" count="200" thumbs="thumbnail" more="true"]
	 */
	function wizhi_shortcode_page_cont( $atts ) {
		$default = [
			'id'     => 1,
			'cont'   => 200,
			'thumbs' => 'thumbnail',
			'more'   => false,
		];
		extract( shortcode_atts( $default, $atts ) );

		$page = get_post( $id );

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

		wp_reset_postdata();
		wp_reset_query();
	}
}
add_shortcode( 'page_cont', 'wizhi_shortcode_page_cont' );


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
			wizhi_get_template_part( 'content', $tmp );
		endwhile;

		wp_reset_postdata();
		wp_reset_query();

	}
}

add_shortcode( 'wizhi_loop', 'wizhi_shortcode_loop' );


if ( ! function_exists( 'wizhi_shortcode_title_list' ) ) {

	/**
	 * 显示文章标题列表
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [title_list type="home" tax="home_tag" tag="yxdt" num="6" cut="26" heading="false"]
	 */
	function wizhi_shortcode_title_list( $atts ) {

		$default = [
			'type'    => 'post',
			'tax'     => 'category',
			'tag'     => 'default',
			'offset'  => 0,
			'num'     => 8, // 数量: 显示文章数量，-1为全部显示
			'heading' => true,
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

		// get term archive name and link
		$cat = get_term_by( 'slug', $tag, $tax );

		if ( $cat ) {
			$cat_name = $cat->name;
			$cat_link = get_term_link( $tag, $tax );
		}

		// 输出
		global $post;
		$the_query = new WP_Query( $args );

		if ( $heading == false || empty( $tax ) ) {
			echo '<div class="ui-list-' . $type . $tag . '">';
			echo '<ul class="ui-list">';

			while ( $the_query->have_posts() ) : $the_query->the_post();
				wizhi_get_template_part( 'content', 'title_list' );
			endwhile;

			echo '</ul>';
			echo '</div>';
		} else {
			echo '<div class="ui-box ' . $type . $tag . '">';
			echo '<div class="ui-box-head">';
			echo '<h3 class="ui-box-head-title"><a href="' . $cat_link . '">' . $cat_name . '</a></h3>';
			echo '<a class="ui-box-head-more" href="' . $cat_link . '" target="_blank">更多></a>';
			echo '</div>';
			echo '<div class="ui-box-container"><ul class="ui-list ui-list-' . $tag . '">';

			while ( $the_query->have_posts() ) : $the_query->the_post();
				wizhi_get_template_part( 'content', 'title_list' );
			endwhile;

			echo '</ul></div></div>';
		}

		wp_reset_postdata();
		wp_reset_query();

	}
}
add_shortcode( 'title_list', 'wizhi_shortcode_title_list' );


if ( ! function_exists( 'wizhi_shortcode_photo_list' ) ) {
	/**
	 * 图文混排样式简码
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [title_list type="home" tax="home_tag" tag="yxdt" num="6" heading="false"]
	 */
	function wizhi_shortcode_photo_list( $atts ) {
		$default = [
			'type'    => 'post',
			'tax'     => 'category',
			'tag'     => 'default',
			'num'     => '4',
			'paged'   => '1',
			'heading' => true,
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

		// 根据分类别名获取分类ID
		$args = [
			'post_type'      => $type,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'posts_per_page' => $num,
			'paged'          => $paged,
			'tax_query'      => $tax_query,
		];

		$cat = get_term_by( 'slug', $tag, $tax );

		if ( $cat ) {
			$cat_name = $cat->name;
			$cat_link = get_term_link( $tag, $tax );
		}

		// 输出
		global $post;
		$wp_query = new WP_Query( $args );

		if ( $heading == false || empty( $tax ) ) {
			echo '<div class="medias media-' . $type . $tag . '">';
			while ( $wp_query->have_posts() ) : $wp_query->the_post();

				wizhi_get_template_part( 'content', 'title_list' );

			endwhile;
			echo '</div>';

		} else {
			echo '<div class="ui-box ' . $type . $tag . '">';
			echo '<div class="ui-box-head">';
			echo '<h3 class="ui-box-head-title"><a href="' . $cat_link . '">' . $cat_name . '</a></h3>';
			echo '<a class="ui-box-head-more" href="' . $cat_link . '" target="_blank">更多></a>';
			echo '</div>';
			echo '<div class="ui-box-container">';
			echo '<div class="ui-box-content">';

			echo '<div class="medias media-' . $tag . '">';

			while ( $wp_query->have_posts() ) : $wp_query->the_post();
				wizhi_get_template_part( 'content', 'photo_list' );
			endwhile;

			echo '</div>';

			echo '</div>';
			echo '</div>';
			echo '</div>';

		}

		wp_reset_postdata();
		wp_reset_query();

	}
}
add_shortcode( 'photo_list', 'wizhi_shortcode_photo_list' );


if ( ! function_exists( 'wizhi_shortcode_slider' ) ) {
	/**
	 * 自适应幻灯
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [slider type="post" tax="category" tag="jingcai" num="4" auto="false" minslides="1" maxslides="4" slidewidth="360", tmp="slider"]
	 */
	function wizhi_shortcode_slider( $atts ) {
		$default = [
			'type'        => 'post',
			'tax'         => 'category',
			'tag'         => 'default',
			'num'         => 8,
			'mode'        => 'horizontal',
			'speed'       => 500,
			'auto'        => true,
			'autohover'   => true,
			'minslides'   => 1,
			'maxslides'   => 1,
			'slidewidth'  => 360,
			'slidewargin' => 10,
			'tmp'         => 'slider',
			'easing'      => 'swing',
		];

		extract( shortcode_atts( $default, $atts ) );

		// 生成 $options 数组
		$id = $tax . '-' . $tag;

		$options = [
			'tax'         => $tax,
			'mode'        => $mode,
			'speed'       => $speed,
			'auto'        => $auto,
			'autohover'   => $autohover,
			'minslides'   => $minslides,
			'maxslides'   => $maxslides,
			'slidewidth'  => $slidewidth,
			'slidemargin' => $slidewargin,
			'easing'      => $easing,
		];

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

		// 生成文章查询参数
		$args = [
			'post_type'      => $type,
			'posts_per_page' => $num,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
			'tax_query'      => $tax_query,
		];

		// 输出
		global $post;
		$wp_query = new WP_Query( $args );

		echo '<div class="bx-box">';
		echo '<ul class="bxslider" id="bxslider-' . $id . '">';

		while ( $wp_query->have_posts() ) : $wp_query->the_post();
			wizhi_get_template_part( 'content', $tmp );
		endwhile;

		echo '</ul></div>';

		wizhi_slider_js( $id, $options );

		wp_reset_postdata();
		wp_reset_query();

	}
}
add_shortcode( 'slider', 'wizhi_shortcode_slider' );


if ( ! function_exists( 'wizhi_slider_js' ) ) {
	/**
	 * 自适应幻灯所需的 JS
	 *
	 * @param $id      int 自适应幻灯的 id
	 * @param $options array 自适应幻灯的控制参数
	 *
	 * @package front
	 *
	 */
	function wizhi_slider_js( $id, $options ) {

		if ( $options[ "maxslides" ] == 1 ) : ?>

			<script>
				jQuery(document).ready(function ($) {
					$('#bxslider-<?php echo $id ?>').bxSlider({
						mode: 'fade',
						captions: true,
						auto: <?php echo $options[ "auto" ] ?>
					});
				});
			</script>

		<?php else : ?>

			<script>
				jQuery(document).ready(function ($) {
					$('#bxslider-<?php echo $id ?>').bxSlider({
						minSlides: <?php echo $options[ "minslides" ] ?>,
						maxSlides: <?php echo $options[ "maxslides" ] ?>,
						slideWidth: <?php echo $options[ "slidewidth" ] ?>,
						slideMargin: <?php echo $options[ "slidemargin" ] ?>,
						infiniteLoop: true,
						hideControlOnEnd: true,
						auto: <?php echo $options[ "auto" ] ?>
					});
				});
			</script>

		<?php endif;

	}
}