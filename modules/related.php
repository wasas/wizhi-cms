<?php

/**
 * 根据标签显示相关文章
 *
 * @param $type string 文章类型名称
 * @param $tax  string 自定义分类法名称
 * @param $num  int    显示的相关文章的数量
 * @param $tmp  string  模板名称，content-$tmp.php
 */
function wizhi_related( $type, $tax, $num, $tmp ) {

	global $post;
	$tags = wp_get_post_terms( $post->ID, $tax );

	if ( $tags ) {
		$tag_ids = array();

		foreach ( $tags as $tag ) {
			$tag_ids[] = $tag->term_id;
		}

		$args = array(
			'post_type'        => $type,
			'tag__in'          => $tag_ids,
			'post__not_in'     => array( $post->ID ),
			'posts_per_page'   => $num,
			'caller_get_posts' => 1
		);

		$related_query = new wp_query( $args );

		while ( $related_query->have_posts() ) : $related_query->the_post();
			get_template_part( 'content', $tmp );
		endwhile;

	}
	wp_reset_query();
}

?>