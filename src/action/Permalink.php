<?php

namespace Wizhi\Action;

/**
 * 为 WordPress 添加自定义链接功能
 */
class Permalink {

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