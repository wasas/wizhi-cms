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
];

$args_term = [
	'id'         => 'template',
	'title'      => __( 'Term template', 'wizhi' ),
	'taxonomies' => [ 'category', 'post_tag', 'prodcat' ],
];

new WizhiTermMetabox( $fields, $args_term );