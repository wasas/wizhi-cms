<?php

$fields = [
	[
		'type'        => 'text',
		'name'        => 'url',
		'label'       => '表单',
		'size'        => '80',
		'default'     => '请输入文本',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'textarea',
		'name'        => 'text',
		'label'       => '文本',
		'size'        => '80',
		'default'     => '请输入文本',
		'placeholder' => '输入文本, 明天更美好',
		'attr'        => [
			'rows' => 5,
			'cols' => 50,
		],
	],
	[
		'type'    => 'checkbox',
		'name'    => 'checkbox',
		'label'   => '文本',
		'size'    => '80',
		'options' => [
			'1' => '老大',
			'2' => '老二',
		],
	],
	[
		'type'    => 'select',
		'name'    => 'pyype',
		'label'   => '文章类型',
		'default' => 'post',
		'options' => wizhi_get_post_types(),
	],
	[
		'type'    => 'select',
		'name'    => 'thmb',
		'label'   => '缩略图大小',
		'default' => 'full',
		'options' => wizhi_get_image_sizes(),
	],
];


$args_post = [
	'id'         => 'extra',
	'title'      => '文章附加数据',
	'post_type' => [ 'post', 'page' ],
	'context'  => 'advanced',
	'priority' => 'high',
];

$args_term = [
	'id'         => 'test',
	'title'      => '测试盒子',
	'taxonomies' => [ 'category', 'post_tag', 'prod_cat' ],
];

$args_widget = [
	'slug'    => 'test1',
	'title' => '测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子',
	'desc'  => '描述一下这个小工具, 看看小工具是有多么不好整',
];

$args_widget2 = [
	'slug'    => 'test222211',
	'title' => '测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子',
	'desc'  => '描述一下这个小工具, 看看小工具是有多么不好整',
];

new WizhiPostMetabox( 'extra', '文章附加数据', $fields, $args_post );

new WizhiTermMetabox( 'test', '测试盒子', $fields, $args_term );

new WizhiUserMetabox( 'user12', '附加资料', $fields );

$wi =  new WizhiWidget( $fields, $args_widget );
$wi2 =  new WizhiWidget( $fields, $args_widget2 );