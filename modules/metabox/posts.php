<?php

$fields = [
	[
		'type'        => 'upload',
		'name'        => '_banner_image',
		'label'       => '封面图像',
		'size'        => '80',
		'placeholder' => '为每个分类设置一个分类图像',
	],
];

$args_post = [
	'id'        => 'extra',
	'title'     => __( ' 封面图像', 'wizhi' ),
	'post_type' => [ 'slider' ],
	'context'   => 'normal',
	'priority'  => 'high',
];


new WizhiPostMetabox( ' banner_image', __( 'Slider Settings', 'wizhi' ), $fields, $args_post );