<?php

namespace Wizhi\Action;

/**
 * 为 WordPress 添加自定义链接功能
 */
class Permalink {


	/**
	 * 当文章设置了自定义链接时，替换默认链接为自定义链接
	 *
	 * @return mixed
	 */
	function custom() {
		global $post;

		$post_id = $post->ID;

		$link = get_post_meta( $post_id, '_link_url', true );

		if ( empty( $link ) ) {
			$link = get_permalink();
		}

		return $link;
	}

}