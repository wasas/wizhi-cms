<?php

$fields = [
	[
		'type'    => 'select',
		'name'    => '_term_template',
		'label'   => __( 'Loop template', 'wizhi' ),
		'options' => wizhi_get_loop_template(),
		'default' => 'list',
	],
	[
		'type'    => 'text',
		'name'    => '_term_posts_per_page',
		'label'   => __( 'Posts per page', 'wizhi' ),
		'size'    => '80',
		'default' => get_option('posts_per_page'),
	],
	[
		'type'        => 'upload',
		'name'        => '_term_banner',
		'label'       => '封面图像',
		'size'        => '80',
		'placeholder' => '为每个分类设置一个分类图像',
	],
];

$args_term = [
	'id'         => 'template',
	'title'      => __( 'Term template', 'wizhi' ),
	'taxonomies' => wizhi_get_taxonomies(),
];

new WizhiTermMetabox( $fields, $args_term );