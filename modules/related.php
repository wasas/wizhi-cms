<?php

/**
 * 根据标签显示相关文章
 *
 * @param string $type 文章类型名称
 * @param string $tax  自定义分类法名称
 * @param int    $num  显示的相关文章的数量
 * @param string $tmp  模板名称，content-$tmp.php
 *
 * @package template
 *
 * @usage wizhi_related('post', 'post_tag', 4, 'related')
 */
function wizhi_related( $type, $tax, $num, $tmp ) {

	global $post;
	$tags = wp_get_post_terms( $post->ID, $tax );

	if ( $tags ) {
		$tag_ids = [ ];

		foreach ( $tags as $tag ) {
			$tag_ids[] = $tag->term_id;
		}

		$args = [
            'post_type'        => $type,
            'post__not_in'     => [ $post->ID ],
            'posts_per_page'   => $num,
            'caller_get_posts' => 1,
            'tax_query' => array(
                array(
                    'taxonomy' => $tax,
                    'field' => 'id',
                    'terms' => $tag_ids
                )
            )
        ];

		$related_query = new wp_query( $args );

		while ( $related_query->have_posts() ) : $related_query->the_post();
			wizhi_get_template_part( 'content', $tmp );
		endwhile;

	}
	wp_reset_query();
}