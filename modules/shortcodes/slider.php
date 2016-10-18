<?php
/**
 * Wizhi Shortcode
 * Wizhi CMS 插件使用的简码
 */

add_shortcode( 'slider', 'wizhi_shortcode_slider' );

if ( ! function_exists( 'wizhi_shortcode_slider' ) ) {
	/**
	 * 自适应幻灯
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [slider type="slider" tax="category" tag="jingcai" num="4" auto="false" minslides="1" maxslides="4" slidewidth="360", tmp="slider"]
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
			'ignore_sticky_posts' => 1,
			'tax_query'      => $tax_query,
		];

		// 输出
		global $post;
		$wp_query = new WP_Query( $args );

		echo '<div class="bx-box">';
		echo '<ul class="bxslider" id="bxslider-' . $id . '">';

		while ( $wp_query->have_posts() ) : $wp_query->the_post();
			echo wizhi_load_template_part( 'content', $tmp );
		endwhile;

		echo '</ul></div>';

		wizhi_slider_js( $id, $options );

		wp_reset_postdata();
		wp_reset_query();

	}
}

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
						adaptiveHeight: true,
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
						adaptiveHeight: true,
						auto: <?php echo $options[ "auto" ] ?>
					});
				});
			</script>

		<?php endif;

	}
}