<?php

/**
 * 判断文章是否包含在父级分类中
 *
 * @param int $cats  分类id
 * @param int $_post 文章id
 *
 * @return boolean
 */
if (!function_exists('post_is_in_descendant_category')) {

	function post_is_in_descendant_category($cats, $_post = null) {
		foreach ((array)$cats as $cat) {
			// get_term_children() accepts integer ID only
			$descendants = get_term_children((int)$cat, 'category');
			if ($descendants && in_category($descendants, $_post)) {
				return true;
			}
		}

		return false;
	}

}

/**
 * 反向转换slug为正常的字符串
 * 
 * @return [type] [description]
 */
function wizhi_unslug($slug=null) {

	if (!$slug) {
		$post_data = get_post($post->ID, ARRAY_A);
    	$slug = $post_data['post_name'];
	}
    
    return ucwords( str_replace("-", " ", $slug) );
}


/**
 * 根据长宽参数，实时缩放图片
 *
 * @param string $url    图片url
 * @param null   $width  图片宽度
 * @param null   $height 图片高度
 * @param bool   $crop   是否强制裁剪到需要的尺寸
 * @param bool   $single
 * @param bool   $upscale
 *
 * @return array|bool|string
 * @throws \Aq_Exception
 * @throws \Exception
 */
if (!function_exists('wizhi_resize')) {

	function wizhi_resize($url, $width = null, $height = null, $crop = true, $single = true, $upscale = false) {

		$wizhi_resize = Aq_Resize::getInstance();

		return $wizhi_resize->process($url, $width, $height, $crop, $single, $upscale);
	}

}



/**
 * 获取缩略图的url
 *
 * @param boolean $post_id  文章id
 * @param string  $size     缩略图尺寸名称
 *
 * @return string 图片url
 */
if (!function_exists('wizhi_get_thumb_src')) {

	function wizhi_get_thumb_src($post_id = null, $size = 'post-thumbnail') {

		$post_id = (null === $post_id) ? get_the_ID() : $post_id;

		$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
		$url = $thumb['0'];

		return $url;
	}

}


/**
 * 获取自定义图片字段的url
 *
 * @param null   $post_id   文章ID
 * @param string $key       自定义字段key
 * @param string $size      缩略图的尺寸名称
 * @param bool   $single    是否为单张
 *
 * @return mixed
 */
if (!function_exists('wizhi_get_meta_src')) {

	function wizhi_get_meta_src($post_id = null, $key = '', $size = 'post-thumbnail', $single = true) {

		$post_id = (null === $post_id) ? get_the_ID() : $post_id;

		$attachment_id = get_post_meta($post_id, $key, $single);
		$attachment_src = wp_get_attachment_image_src($attachment_id, $size);

		return $attachment_src['0'];
	}
}


/**
 * 实时缩放缩略图
 *
 * @param string $url    图片url
 * @param null   $width  图片宽度
 * @param null   $height 图片高度
 * @param bool   $crop   是否强制裁剪到需要的尺寸
 * @param bool   $single
 * @param bool   $upscale
 *
 * @return array|bool|string
 * @throws \Aq_Exception
 * @throws \Exception
 */
if (!function_exists('wizhi_get_thumbnail')) {

	function wizhi_get_thumbnail($post_id = null, $width = null, $height = null, $crop = null, $single = true, $upscale = true) {

		$post_id = (null === $post_id) ? get_the_ID() : $post_id;
		$url = wizhi_get_thumb_src($post_id, "full");

		return wizhi_resize($url, $width, $height, $crop, $single, $upscale);
	}

}


/**
 * 实时缩放自定义字段缩略图
 *
 * @param null   $post_id   文章id
 * @param string $key       自定义字段key
 * @param null   $width     图片宽度
 * @param null   $height    图片高度
 * @param null   $crop      是否强制裁剪
 * @param bool   $single
 * @param bool   $upscale
 *
 * @return array|bool|string
 */
if (!function_exists('wizhi_get_meta_thumbnail')) {

	function wizhi_get_meta_thumbnail($post_id = null, $key = '', $width = null, $height = null, $crop = null, $single = true, $upscale = true) {

		$url = wizhi_get_meta_src($post_id, $key, 'full', true);

		return wizhi_resize($url, $width, $height, $crop, $single, $upscale);
	}

}


/**
 * 获取文章或分类项目的自定义字段值
 *
 * @param string $key    meta_key
 * @param bool   $single return string or array
 *
 * @return mixed
 */
if (!function_exists('wizhi_get_meta')) {

	function wizhi_get_meta($key = '', $single = false) {

		$pid = get_queried_object_id();

		if (is_singular()) {
			$value = get_post_meta($pid, $key, $single);
		} else {
			$value = get_term_meta($pid, $key, $single);
		}

		return $value;
	}

}


/**
 * 获取分类项目名称
 *
 * @since 2.5.0
 *
 * @param int $id Post ID.
 * @param string $taxonomy Taxonomy name.
 * @param string $before Optional. Before list.
 * @param string $sep Optional. Separate items using this.
 * @param string $after Optional. After list.
 * @return string|bool|WP_Error A list of terms on success, false if there are no terms, WP_Error on failure.
 */
if (!function_exists('wizhi_the_term_names')) {

    function wizhi_the_term_names($id, $taxonomy, $before = '', $sep = '', $after = '') {

        $terms = get_the_terms( $id, $taxonomy );

        if ( is_wp_error( $terms ) )
            return $terms;

        if ( empty( $terms ) )
            return false;

        $links = array();

        foreach ( $terms as $term ) {
            $link = get_term_link( $term, $taxonomy );
            if ( is_wp_error( $link ) ) {
                return $link;
            }
            $links[] = $term->name;
        }

        /**
         * Filter the term links for a given taxonomy.
         *
         * The dynamic portion of the filter name, `$taxonomy`, refers
         * to the taxonomy slug.
         *
         * @since 2.5.0
         *
         * @param array $links An array of term links.
         */
        $term_links = apply_filters( "term_links-$taxonomy", $links );

        return $before . join( $sep, $term_links ) . $after;

    }

}