<?php
/*
Title: 首页图片
Post Type: product
Order: 0
Collapse: false
*/

piklist(
	'field',
	array(
		'type'        => 'file',
		'field'       => 'pro_home',
		'scope'       => 'post_meta',
		'description' => __('首页用，大图为470*470px，小图为230*230px', 'piklist'),
		'options'     => array(
			'modal_title' => __('添加图片', 'piklist'),
			'button'      => __('添加', 'piklist')
		)
	));


?>