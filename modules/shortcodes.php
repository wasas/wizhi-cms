<?php
/**
 * Wizhi Shortcode
 *
 */

/*-----------------------------------------------------------------------------------*/
/* 显示文章
/*-----------------------------------------------------------------------------------*/

/* 以下为自定义功能，在主题升级的时候，直接拷贝到相关文件内即可。
 * 增加了文章标题列表和slider 幻灯共4中简码功能。
 * 使用方法：<?php echo do_shortcode('[page_cont id="1" cut="20" more="ture"]'); ?>
 */


/* 获取某个页面的内容 */

if ( ! function_exists( 'wizhi_shortcode_page_cont' ) ) {
	function wizhi_shortcode_page_cont( $atts ) {
		$default = array(
			'id'   => 1,
			'cut'  => 200,
			'more' => true
		);
		extract( shortcode_atts( $default, $atts ) );

		$page = get_post( $id );

		// 输出
		$retour = '';
		$retour .= wp_trim_words( $page->post_content, $cut, "..." );
		if ( $more == true ) {
			$retour .= '<a target="_blank" href="' . get_page_link( $id ) . '">更多>></a>';
		} else {
			$retour .= '';
		}

		wp_reset_postdata();
		wp_reset_query();

		return $retour;

	}
}
add_shortcode( 'page_cont', 'wizhi_shortcode_page_cont' );


/* 根据自定义分类显示文章
 * 输出标题文章列表时实现，默认带标题
 * 使用方法：[wizhi_loop type="home" tax="home_tag" tag="yxdt" num="6" cut="26" heading="false" time="true" sticky="true"]
 * todo：可以实现更多的参数控制
*/
if ( ! function_exists( 'wizhi_shortcode_loop' ) ) {

	function wizhi_shortcode_loop( $atts ) {

		$default = array(
			'type'    => 'post',
			'tax'     => 'category',
			'tag'     => 'default',
			'tp'      => 'lists',
			'offset'  => 0,
			'num'     => 8, // 数量: 显示文章数量，-1为全部显示
		);

		extract( shortcode_atts( $default, $atts ) );

		// 判断是否查询分类
		if ( empty( $tax ) ) {
			$tax_query = '';
		} else {
			$tax_query = array(
				array(
					'taxonomy' => $tax,
					'field'    => 'slug',
					'terms'    => $tag,
				)
			);
		}

		// 构建文章查询数组
		$args = array(
			'post_type'      => $type,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'posts_per_page' => $num,
			'offset'         => $offset,
			'tax_query'      => $tax_query
		);

		// 输出
		$the_query = new WP_Query( $args );

		while ( $the_query->have_posts() ) : $the_query->the_post();
			get_template_part( 'content', $tp );
		endwhile;

		wp_reset_postdata();
		wp_reset_query();

	}
}

add_shortcode( 'wizhi_loop', 'wizhi_shortcode_loop' );


/* 根据自定义分类显示文章
 * 输出标题文章列表时实现，默认带标题
 * 使用方法：[title_list type="home" tax="home_tag" tag="yxdt" num="6" cut="26" heading="false" time="true" sticky="true"]
 * todo：可以实现更多的参数控制
*/
if ( ! function_exists( 'wizhi_shortcode_title_list' ) ) {

	function wizhi_shortcode_title_list( $atts ) {

		$default = array(
			'type'    => 'post',
			'tax'     => 'category',
			'tag'     => 'default',
			'offset'  => 0,
			'num'     => 8, // 数量: 显示文章数量，-1为全部显示
			'cut'     => 36, // 切断：标题截取的字符数
			'heading' => true,
			'time'    => false,
		);

		extract( shortcode_atts( $default, $atts ) );

		// 判断是否查询分类
		if ( empty( $tax ) ) {
			$tax_query = '';
		} else {
			$tax_query = array(
				array(
					'taxonomy' => $tax,
					'field'    => 'slug',
					'terms'    => $tag,
				)
			);
		}

		// 构建文章查询数组
		$args = array(
			'post_type'      => $type,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'posts_per_page' => $num,
			'offset'         => $offset,
			'tax_query'      => $tax_query
		);

		// get term archive name and link
		$cat      = get_term_by( 'slug', $tag, $tax );

    if ($cat) {
      $cat_name = $cat->name;
      $cat_link = get_term_link( $tag, $tax );
    }
		
		// 输出
		global $post;
		$the_query = new WP_Query( $args );

		$retour = '';
		if ( $heading == false || empty( $tax ) ) {
			$retour .= '<div class="zui-list-' . $type . $tag . '">';
			$retour .= '<ul class="zui-list">';
			while ( $the_query->have_posts() ) : $the_query->the_post();

				//custom links
				$cus_links = get_post_meta( get_the_ID(), 'cus_links', true );
				if ( empty( $cus_links ) ) {
					$cus_links = get_permalink();
				}

				$retour .= '<li class="zui-list-item">';
				if ( $time == 'true' ) {
					$retour .= '<span class="pull-right time">' . get_the_time( 'm-d' ) . '</span>';
				} else {
					$retour .= '';
				}
				$retour .= '<a href="' . $cus_links . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
				$retour .= '</li>';
			endwhile;
			$retour .= '</ul>';
			$retour .= '</div>';
		} else {
			$retour .= '<div class="zui-box ' . $type . $tag . '">';
			$retour .= '<div class="zui-box-head">';
			$retour .= '<h3 class="zui-box-head-title"><a href="' . $cat_link . '">' . $cat_name . '</a></h3>';
			$retour .= '<a class="more pull-right" href="' . $cat_link . '" target="_blank">更多></a>';
			$retour .= '</div>';
			$retour .= '<div class="zui-box-container"><ul class="zui-list zui-list-' . $tag . '">';
			while ( $the_query->have_posts() ) : $the_query->the_post();

				//custom links
				$cus_links = get_post_meta( get_the_ID(), 'cus_links', true );
				if ( empty( $cus_links ) ) {
					$cus_links = get_permalink();
				}

				$retour .= '<li class="zui-list-item">';
				if ( $time == 'true' ) {
					$retour .= '<span class="pull-right time">' . get_the_time( 'm-d' ) . '</span>';
				} else {
					$retour .= '';
				}
				$retour .= '<a href="' . $cus_links . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
				$retour .= '</li>';
			endwhile;
			$retour .= '</ul></div></div>';
		}

		wp_reset_postdata();
		wp_reset_query();

		return $retour;

	}
}
add_shortcode( 'title_list', 'wizhi_shortcode_title_list' );


/* 图文混排样式简码
 * 需要的参数：文章类型，分类法，分类，缩略图别名，标题字数，是否显示时间，内容字数
 * 使用方法：<?php echo do_shortcode('[photo_list type="home" tax="home_tag" tag="yxdt" num="6" cut="26" heading="false" time="true" thumbs="maintain" cut="6" sticky="true" class="pure-u-1-5"]'); ?>
 */
if ( ! function_exists( 'wizhi_shortcode_photo_list' ) ) {

	function wizhi_shortcode_photo_list( $atts ) {
		$default = array(
			'type'     => 'post',
			'tax'      => 'category',
			'tag'      => 'default',
			'thumbs'   => 'tumbnails',
			'position' => 'left',
			'num'      => '4',
			'paged'    => '1',
			'cut'      => '',
			'content'  => '',
			'heading'  => true,
			'class'    => 'pure-u-1-4'
		);

		extract( shortcode_atts( $default, $atts ) );

		// 判断是否查询分类
		if ( empty( $tax ) ) {
			$tax_query = '';
		} else {
			$tax_query = array(
				array(
					'taxonomy' => $tax,
					'field'    => 'slug',
					'terms'    => $tag,
				)
			);
		}

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		// 根据分类别名获取分类ID
		$args = array(
			'post_type'      => $type,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'posts_per_page' => $num,
			'paged'          => $paged,
			'tax_query'      => $tax_query
		);

		$cat      = get_term_by( 'slug', $tag, $tax );

    if ($cat) {
      $cat_name = $cat->name;
      $cat_link = get_term_link( $tag, $tax );
    }

		if ( $position == "left" ) {
			$position = "zui-media-cap-left";
		} elseif ( $position == "right" ) {
			$position = "zui-media-cap-right";
		} else {
			$position = "zui-media-cap-top";
		}

		// 输出
		global $post;
		$wp_query = new WP_Query( $args );
		$retour   = '';

		if ( $heading == false || empty( $tax ) ) {
			$retour .= '<div class="zui-medias zui-media-' . $type . $tag . '">';
			while ( $wp_query->have_posts() ) : $wp_query->the_post();

				//custom links
				$cus_links = get_post_meta( get_the_ID(), 'cus_links', true );
				if ( empty( $cus_links ) ) {
					$cus_links = get_permalink();
				}

				$retour .= '<div class="' . $class . '">';
				$retour .= '<div class=" zui-media">';
				if ( ! empty( $thumbs ) ) {
					$retour .= '<a class="zui-media-cap ' . $position . '" target="_blank" href="' . $cus_links . '">';
					if ( has_post_thumbnail() ) {
						$retour .= get_the_post_thumbnail( $post->ID, $thumbs );
					}
					$retour .= '</a>';
				}
				if ( ! empty( $content ) ) {
					$retour .= '<div class="zui-media-body">';
					$retour .= '<div class="zui-media-body-title"><a href="' . $cus_links . '">' . wp_trim_words( $post->post_title, $cut, "..." ) . '</a></div>';
					$retour .= wp_trim_words( $post->post_content, $content, "..." );
					$retour .= '</div>';
				} else {
					if ( ! empty( $cut ) ) {
						$retour .= '<a href="' . $cus_links . '">' . wp_trim_words( $post->post_title, $cut, "..." ) . '</a>';
					}
				}
				$retour .= '</div>';
				$retour .= '</div>';

			endwhile;
			$retour .= '</div>';

		} else {
			$retour .= '<div class="zui-box ' . $type . $tag . '">';
			$retour .= '<div class="zui-box-head">';
			$retour .= '<h3 class="zui-box-head-title"><a href="' . $cat_link . '">' . $cat_name . '</a></h3>';
			$retour .= '<a class="more pull-right" href="' . $cat_link . '" target="_blank">更多></a>';
			$retour .= '</div>';
			$retour .= '<div class="zui-box-container">';
			$retour .= '<div class="zui-box-content">';

			$retour .= '<div class="zui-medias zui-media-' . $tag . '">';
			while ( $wp_query->have_posts() ) : $wp_query->the_post();

				//custom links
				$cus_links = get_post_meta( $post->ID, 'cus_links', true );
				if ( empty( $cus_links ) ) {
					$cus_links = get_permalink();
				}

				setup_postdata( $post );
				$retour .= '<div class="' . $class . '">';
				$retour .= '<div class="zui-media">';
				if ( ! empty( $thumbs ) ) {
					$retour .= '<a class="zui-media-cap ' . $position . '" target="_blank" href="' . $cus_links . '">';
					if ( has_post_thumbnail() ) {
						$retour .= get_the_post_thumbnail( $post->ID, $thumbs );
					}
					$retour .= '</a>';
				}
				if ( ! empty( $content ) ) {
					$retour .= '<div class="zui-media-body">';
					$retour .= '<div class="zui-media-body-title"><a href="' . $cus_links . '">' . wp_trim_words( $post->post_title, $cut, "..." ) . '</a></div>';
					$retour .= wp_trim_words( $post->post_content, $content, "..." );
					$retour .= '</div>';
				} else {
					if ( ! empty( $cut ) ) {
						$retour .= '<a href="' . $cus_links . '">' . wp_trim_words( $post->post_title, $cut, "..." ) . '</a>';
					}
				}
				$retour .= '</div>';
				$retour .= '</div>';
			endwhile;
			$retour .= '</div>';

			$retour .= '</div>';
			$retour .= '</div>';
			$retour .= '</div>';

		}

		wp_reset_postdata();
		wp_reset_query();

		return $retour;

	}
}
add_shortcode( 'photo_list', 'wizhi_shortcode_photo_list' );


/* 分类自适应幻灯
 * 替代方案为上面的slider幻灯，在性能上比较好
 * 存在显示上的一些问题
 * 使用方法：<?php echo do_shortcode('[slider type="post" tax="category" tag="jingcai" speed="1000" num="4" thumbs="full" cut="46"]'); ?>
 */

if ( ! function_exists( 'wizhi_shortcode_slider' ) ) {

	function wizhi_shortcode_slider( $atts ) {
		$default = array(
			'type'        => 'post',
			'tax'         => 'category',
			'tag'         => 'default',
			'num'         => 8,
			'cut'         => 36,
			'thumbs'      => 'show',
			'mode'        => 'horizontal',
			'speed'       => 500,
			'auto'        => true,
			'autohover'   => true,
			'minslides'   => 1,
			'maxslides'   => 1,
			'slidewidth'  => 360,
			'slidewargin' => 10,
			'easing'      => 'swing',
		);

		extract( shortcode_atts( $default, $atts ) );

		// 生成 $options 数组
		$cat      = get_term_by( 'slug', $tag, $tax );

    if ($cat) {
      $cat_name = $cat->name;
      $cat_link = get_term_link( $tag, $tax );
    }

		$id = $tax . $tag;

		$options = array(
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
		);

		// 生成文章查询参数
		$args = array(
			'post_type'      => $type,
			'posts_per_page' => $num,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
			'tax_query'      => array(
				array(
					'taxonomy' => $tax,
					'field'    => 'slug',
					'terms'    => $tag,
				)
			)
		);

		// 输出
		global $post;
		$wp_query = new WP_Query( $args );

		$retour = '<div class="bx-box">';
		$retour .= '<ul class="bxslider fix" id="bxslider-' . $id . '">';

		while ( $wp_query->have_posts() ) : $wp_query->the_post();

			// custom links
			$cus_links = get_post_meta( $post->ID, 'cus_links', true );
			if ( empty( $cus_links ) ) {
				$cus_links = get_permalink();
			}

			$retour .= '<li class="bx-item">';
			$retour .= '<a target="_blank" class="item-' . $tax . ' " href="' . $cus_links . '" title="' . get_the_title() . '">';
			if ( has_post_thumbnail() ) {
				$retour .= get_the_post_thumbnail( $post->ID, $thumbs );
			}
			$retour .= '<p class="bx-caption"><span>' . wp_trim_words( $post->post_title, $cut, "..." ) . '</span></p>';
			$retour .= '</a>';
			$retour .= '</li>';

		endwhile;
		$retour .= '</ul></div>';

		wizhi_slider_js( $id, $options );

		wp_reset_postdata();
		wp_reset_query();

		return $retour;

	}
}
add_shortcode( 'slider', 'wizhi_shortcode_slider' );


/**-----------------------------------------------------------------------------------*/
/* Slider Javascript
/* Jquery Cycle 幻灯所需的JS
/* -----------------------------------------------------------------------------------
*/

if ( ! function_exists( 'wizhi_slider_js' ) ) {
	function wizhi_slider_js( $id, $options ) {

		if ( $options["maxslides"] == 1 ) : ?>

			<script>
				jQuery(document).ready(function ($) {
					$('#bxslider-<?php echo $id ?>').bxSlider({
						mode: 'fade',
						captions: true
					});
				});
			</script>

		<?php else : ?>

			<script>
				jQuery(document).ready(function ($) {
					$('#bxslider-<?php echo $id ?>').bxSlider({
						minSlides: <?php echo $options["minslides"] ?>,
						maxSlides: <?php echo $options["maxslides"] ?>,
						slideWidth: <?php echo $options["slidewidth"] ?>,
						slideMargin: <?php echo $options["slidemargin"] ?>,
						infiniteLoop: false,
						hideControlOnEnd: true
					});
				});
			</script>

		<?php endif;

	}
}