<?php
/**
 * 默认文章类型的自定义数据盒子
 *
 */

namespace Wizhi\Metabox;

class Post {

	/**
	 * 默认文章自定义字段
	 */
	public static function init() {

		$types = [ 'page' ];

		$fields = [
			"_banner_image" => new \Fieldmanager_Media( __( 'Cover image', 'wizhi' ) ),
			"_banner_text"  => new \Fieldmanager_RichTextArea( __( 'Cover Text', 'wizhi' ) ),
		];

		$fm = new \Fieldmanager_Group( [
			'name'           => 'wizhi_post_metas',
			'serialize_data' => false,
			'add_to_prefix'  => false,
			'children'       => apply_filters( 'wizhi_post_fields', $fields ),
		] );

		$fm->add_meta_box( __( 'Post Fields', 'wizhi' ), apply_filters( 'wizhi_post_fields_supports', $types ) );

	}
}

