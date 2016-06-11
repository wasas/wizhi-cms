<?php

$fields = [
	[
		'type'    => 'select',
		'name'    => '_term_template',
		'label'   => '分类循环模板',
		'size'    => '80',
		'options' => wizhi_get_loop_template(),
	],
	[
		'type'    => 'text',
		'name'    => '_term_posts_per_page',
		'label'   => '每页显示文章数量',
		'size'    => '80',
		'default' => get_option('posts_per_page'),
	],
];

$args_term = [
	'id'         => 'template',
	'title'      => '分类模板',
	'taxonomies' => [ 'category', 'post_tag', 'prodcat' ],
];

new WizhiTermMetabox( $fields, $args_term );