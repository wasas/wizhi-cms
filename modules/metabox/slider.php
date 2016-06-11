<?php

$fields = [
	[
		'type'    => 'text',
		'name'    => '_slider_url',
		'label'   => __( 'Link url', 'wizhi' ),
		'size'    => '80',
		'default' => 'http://',
	],
];

$args_post = [
	'id'        => 'extra',
	'title'     => __( 'Slider Settings', 'wizhi' ),
	'post_type' => [ 'slider' ],
	'context'   => 'normal',
	'priority'  => 'high',
];


new WizhiPostMetabox( 'slider_data', __( 'Slider Settings', 'wizhi' ), $fields, $args_post );