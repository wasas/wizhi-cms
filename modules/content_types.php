<?php

/**
 * 为每个文章类型添加存档设置
 *
 * @param $type
 * @param $name
 */
function add_type_options( $type, $name ) {
	$fields = [
		[
			'type'    => 'select',
			'name'    => $type . "_archive_template",
			'label'   => __( 'Archive template', 'wizhi' ),
			'options' => wizhi_get_loop_template(),
			'default' => 'list',
		],
		[
			'type'    => 'text',
			'name'    => $type . "_archive_per_page",
			'label'   => __( 'Posts per page', 'wizhi' ),
			'size'    => '80',
			'default' => get_option( $type . "_archive_per_page" ),
		],
		[
			'type'        => 'upload',
			'name'        => $type . "_archive_banner_image",
			'label'       => '封面图像',
			'size'        => '80',
			'placeholder' => '为每个分类设置一个分类图像',
		],
		[
			'type'        => 'textarea',
			'name'        => $type . "_archive_description",
			'label'       => '存档描述',
			'size'        => '80',
			'placeholder' => '',
			'attr'        => [
				'rows' => 5,
				'cols' => 50,
			],
		],
	];


	$args = [
		'parent' => 'edit.php?post_type=' . $type,
		'slug'   => "archive_" . $type . "_settings",
		'label'  => __( '存档设置', 'wizhi' ),
		'title'  => __( $name . '存档设置', 'wizhi' ),
	];

	new WizhiOptionPage( $fields, $args );
}


/**
 * 获取文章类型存档设置
 *
 * @param string $type 文章类型别名
 * @param string $name 设置名称
 *
 * @return mixed
 */
function get_archive_option( $type, $name ) {
	return get_option( $type . "_archive_" . $name );
}


/**
 * 添加默认的自定义文章类型和分类法
 */
function add_default_content_type() {

	$types              = wizhi_post_types();
	$icons              = wizhi_post_types_icon();
	$enabled_post_types = get_option( 'enabled_post_types', [ ] );

	if ( count( $enabled_post_types ) > 0 ) {
		// 添加默认的文章类型和分类方法
		foreach ( $enabled_post_types as $slug ) {
			wizhi_create_types( $slug, $types[ $slug ], [ 'title', 'editor', 'thumbnail' ], true, $icons[ $slug ] );
			wizhi_create_taxs( $slug . 'cat', $slug, $types[ $slug ] . __( 'Category', 'wizhi' ), true );

			add_type_options( $slug, $types[ $slug ] );
		}
	}

}

add_action( 'init', 'add_default_content_type' );