<?php

add_action( 'wp_loaded', 'show_select' );
function show_select() {

	$fields = [
		[
			'type'   => 'container',
			'name'   => 'urlstext',
			'label'  => '表单组',
			'size'   => '80',
			'fields' => [
				[
					'type'        => 'text',
					'name'        => 'first_name',
					'label'       => '名字',
					'size'        => '80',
					'placeholder' => '请输入名字',
				],
				[
					'type'        => 'text',
					'name'        => 'second_name',
					'label'       => '姓氏',
					'size'        => '80',
					'placeholder' => '请输入形式',
				],
			],
		],
		[
			'type'        => 'text',
			'name'        => 'prod_name',
			'label'       => '商品名称',
			'size'        => '80',
			'desc'        => '输入商品的名称,可以适当的加上属性',
			'placeholder' => '商品名称',
		],
		[
			'type'        => 'email',
			'name'        => 'email',
			'label'       => '联系邮件',
			'size'        => '80',
			'placeholder' => '请输入商品的联系邮件',
		],
		[
			'type'        => 'url',
			'name'        => 'prod_url',
			'label'       => '网址',
			'size'        => '80',
			'placeholder' => 'http://',
		],
		[
			'type'        => 'number',
			'name'        => 'prod_count',
			'label'       => '库存数量',
			'size'        => '80',
			'placeholder' => '有多少库存',
		],
		[
			'type'        => 'date',
			'name'        => 'end_date',
			'label'       => '截至日期',
			'size'        => '80',
			'placeholder' => '可以销售到什么时候',
		],
		[
			'type'        => 'month',
			'name'        => 'prod_month',
			'label'       => '适合月份',
			'size'        => '80',
			'placeholder' => '商品适合在什么月份使用',
		],
		[
			'type'        => 'week',
			'name'        => 'weeks',
			'label'       => '可用周数',
			'size'        => '80',
			'placeholder' => '商品可以使用多少周',
		],

		[
			'type'        => 'time',
			'name'        => 'send_time',
			'label'       => '发货时间',
			'size'        => '80',
			'placeholder' => '商品发货时间',
		],

		[
			'type'        => 'datetime-local',
			'name'        => 'local_datetime',
			'label'       => '本地时间',
			'size'        => '80',
			'placeholder' => '本地时区的时间',
		],

		[
			'type'        => 'search',
			'name'        => 'search',
			'label'       => '搜索字段',
			'size'        => '80',
			'placeholder' => '可以增加搜索提示',
		],
		[
			'type'        => 'editor',
			'name'        => 'prod_desc',
			'label'       => '商品简介',
			'size'        => '80',
			'placeholder' => '商品的简单介绍, 不要输入太多',
			'attr'        => [
				'rows' => 5,
				'cols' => 50,
			],
		],
		[
			'type'        => 'color',
			'name'        => 'urls_color',
			'label'       => '商品颜色',
			'size'        => '80',
			'placeholder' => '商品颜色,请直接选择',
		],
		[
			'type'        => 'editor',
			'name'        => 'prod_detail',
			'label'       => '商品详细信息',
			'size'        => '80',
			'placeholder' => '商品详情, 尽量详细的介绍',
			'attr'        => [
				'rows' => 5,
				'cols' => 50,
			],
		],
		[
			'type'        => 'upload',
			'name'        => 'prod_image',
			'label'       => '商品主图',
			'size'        => '80',
			'placeholder' => '商品的主要图片, 尽量清晰, 具有吸引力',
		],
		[
			'type'        => 'editor',
			'name'        => 'prod_intro',
			'label'       => '使用说明',
			'size'        => '80',
			'placeholder' => '商品使用说明, 语言尽量简洁',
			'attr'        => [
				'rows' => 5,
				'cols' => 50,
			],
		],
		[
			'type'    => 'checkbox',
			'name'    => 'is_deliver',
			'label'   => '是否发货',
			'size'    => '80',
		],
		[
			'type'    => 'multi-checkbox',
			'name'    => 'pay_method',
			'label'   => '支付方式',
			'size'    => '80',
			'options' => [
				'4' => '微信',
				'5' => '支付宝',
			],
		],
		[
			'type'    => 'radio',
			'name'    => 'pod',
			'label'   => '是否支持货到付款',
			'size'    => '80',
			'default' => '1',
			'options' => [
				'1' => '支持',
				'2' => '不支持',
			],
		],
		[
			'type'    => 'select',
			'name'    => 'content_type',
			'label'   => '文章类型',
			'default' => 'post',
			'options' => wizhi_get_post_types(),
		],
		[
			'type'    => 'select',
			'name'    => 'thumb_size',
			'label'   => '缩略图大小',
			'default' => 'full',
			'options' => wizhi_get_image_sizes(),
		],
	];


	$args_post = [
		'post_type' => [ 'post', 'page' ],
		'context'   => 'normal',
		'priority'  => 'high',
	];

	$args_term = [
		'id'         => 'test',
		'title'      => '测试盒子',
		'taxonomies' => [ 'category', 'post_tag', 'prod_cat' ],
	];

	$args_option = [
		'slug'  => 'wizhi-demo-settings',
		'label' => __( '插件设置演示', 'wizhi' ),
		'title' => __( 'Wizhi CMS 插件设置', 'wizhi' ),
	];


	new WizhiPostMetabox( 'extra', '附加数据', $fields, $args_post );

	new WizhiTermMetabox( $fields, $args_term );

	new WizhiUserMetabox( $fields );

	new WizhiOptionPage( $fields, $args_option );
}

