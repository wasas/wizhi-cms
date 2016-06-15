<?php

$fields = [
	[
		'type'  => 'group',
		'label' => '群组1',
	],
	[
		'type'    => 'container',
		'name'    => 'urlstext',
		'label'   => '表单组',
		'size'    => '80',
		'fields' => [
			[
				'type'        => 'text',
				'name'        => 'uname',
				'label'       => ' 姓名',
				'size'        => '80',
				'placeholder' => '输入文本, 明天更美好',
			],
			[
				'type'        => 'text',
				'name'        => 'email',
				'label'       => '邮件',
				'size'        => '80',
				'placeholder' => '输入文本, 明天更美好',
			],
		],
	],
	[
		'type'        => 'text',
		'name'        => 'urls',
		'label'       => '文本',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'email',
		'name'        => 'urls_email',
		'label'       => '邮件',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'url',
		'name'        => 'urls_url',
		'label'       => '网址',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],[
		'type'        => 'number',
		'name'        => 'urls_number',
		'label'       => '数字',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'date',
		'name'        => 'urls_date',
		'label'       => '日期',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'month',
		'name'        => 'urls_month',
		'label'       => '月份',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'week',
		'name'        => 'urls_week',
		'label'       => '周数',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],

	[
		'type'        => 'time',
		'name'        => 'urls_time',
		'label'       => '时间',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],

	[
		'type'        => 'datetime-local',
		'name'        => 'urls_datetime',
		'label'       => '本地时间日期',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],

	[
		'type'        => 'search',
		'name'        => 'urls_search',
		'label'       => '搜索',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'color',
		'name'        => 'urls_color',
		'label'       => '颜色',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'upload',
		'name'        => 'ups',
		'label'       => '上传文件',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'textarea',
		'name'        => 'text',
		'label'       => '文本',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
		'attr'        => [
			'rows' => 5,
			'cols' => 50,
		],
	],
	[
		'type'  => 'group',
		'label' => '群组2',
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
		'type'  => 'group',
		'label' => '群组3',
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

$fields2 = [
	[
		'type'        => 'text',
		'name'        => 'url',
		'label'       => '表单',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'textarea',
		'name'        => 'text',
		'label'       => '文本',
		'size'        => '80',
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
];


$args_post = [
	'id'        => 'extra',
	'title'     => '文章附加数据',
	'post_type' => [ 'post', 'page' ],
	'context'   => 'normal',
	'priority'  => 'high',
];

$args_term = [
	'id'         => 'test',
	'title'      => '测试盒子',
	'taxonomies' => [ 'category', 'post_tag', 'product_cat' ],
];

$args_widget = [
	'slug'  => 'test1',
	'title' => '测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子测试盒子',
	'desc'  => '描述一下这个小工具, 看看小工具是有多么不好整',
];


new WizhiPostMetabox( 'extra', '文章附加数据', $fields, $args_post );

new WizhiTermMetabox( $fields, $args_term );

new WizhiUserMetabox( $fields );