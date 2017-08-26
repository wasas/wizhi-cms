<?php
/**
 * Created by PhpStorm.
 * User: amoslee
 * Date: 2017/8/26
 * Time: 22:42
 */

namespace Wizhi\Action;


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