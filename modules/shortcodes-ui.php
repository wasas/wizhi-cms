<?php
/**
 * Wizhi Shortcode UI by shortcacke
 *
 */


// 显示页面内容
add_action( 'init', 'wizhi_shortcode_page_cont_ui' );

function wizhi_shortcode_page_cont_ui() {

	// 检测 Shortcake 插件功能是否存在
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	// 只有管理员可以使用
	if ( ! is_admin() ) {
		return;
	}

	// 显示按钮
	shortcode_ui_register_for_shortcode( 'button', [
		'label'         => '按钮',
		'listItemImage' => 'dashicons-external',
		'attrs'         => [
			[
				'label'   => __( '按钮颜色' ),
				'attr'    => 'type',
				'type'    => 'select',
				'options' => [
					'ok'      => '绿色',
					'warning' => '黄色',
					'error'   => '红色',
				],
			],
			[
				'label'   => __( '按钮尺寸' ),
				'attr'    => 'size',
				'type'    => 'select',
				'options' => [
					'small'  => '小尺寸',
					'large'  => '大尺寸',
					'xlarge' => '特大尺寸',
				],
			],
			[
				'label' => __( '按钮文字' ),
				'attr'  => 'text',
				'type'  => 'text',
				'meta'  => [
					'placeholder' => '请输入链接文字',
				],
			],
			[
				'label' => __( '按钮连接' ),
				'attr'  => 'url',
				'type'  => 'url',
				'meta'  => [
					'placeholder' => 'http://',
				],
			],
		],
	] );


	// 显示分割线
	shortcode_ui_register_for_shortcode( 'divider', [
		'label'         => '分割线',
		'listItemImage' => 'dashicons-minus',
		'attrs'         => [
			[
				'label'   => __( '分割线类型' ),
				'attr'    => 'type',
				'type'    => 'select',
				'options' => [
					'solid'  => '实线',
					'shadow' => '投影',
				],
			],
		],
	] );


	// 显示内容标题
	shortcode_ui_register_for_shortcode( 'heading', [
			'label'         => '标题',
			'listItemImage' => 'dashicons-editor-bold',
			'attrs'         => [
				[
					'label'   => __( '标题类型' ),
					'attr'    => 'type',
					'type'    => 'select',
					'options' => [
						'background' => '背景',
						'border'     => '底部边框',
					],
				],
				[
					'label' => __( '内容' ),
					'attr'  => 'content',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => '请输入标题文字',
					],
				],
			],
		]

	);


	// 显示提示信息
	shortcode_ui_register_for_shortcode( 'alert', [
		'label'         => '提示信息',
		'listItemImage' => 'dashicons-info',
		'attrs'         => [
			[
				'label'   => __( '信息类型' ),
				'attr'    => 'type',
				'type'    => 'select',
				'options' => [
					'success' => '成功提示（绿色））',
					'warning' => '警告提示（黄色））',
					'error'   => '失败提示（红色））',
				],
			],
			[
				'label' => __( '提示内容' ),
				'attr'  => 'content',
				'type'  => 'textarea',
				'meta'  => [
					'placeholder' => '请输入提示信息内容',
				],
			],
		],
	] );

	// 创建显示页面内容UI
	shortcode_ui_register_for_shortcode( 'page_cont', [
		'label'         => '页面内容',
		'listItemImage' => 'dashicons-media-document',
		'attrs'         => [
			[
				'label' => __( '页面ID' ),
				'attr'  => 'id',
				'type'  => 'post_select',
				'query' => [ 'post_type' => 'page' ],
			],
			[
				'label' => __( '内容字数' ),
				'attr'  => 'cont',
				'type'  => 'text',
				'value' => '120',
			],
			[
				'label' => __( '显示更多链接' ),
				'attr'  => 'more',
				'type'  => 'checkbox',
				'value' => true,
			],
		],
	] );


	// 创建文章列表UI
	shortcode_ui_register_for_shortcode( 'title_list', [
		'label'         => '文章标题列表',
		'listItemImage' => 'dashicons-media-text',
		'attrs'         => [
			[
				'label' => __( '文章类型' ),
				'attr'  => 'type',
				'type'  => 'text',
				'value' => 'post',
			],
			[
				'label' => __( '分类方法' ),
				'attr'  => 'tax',
				'type'  => 'text',
				'value' => 'category',
			],
			[
				'label' => __( '分类项目' ),
				'attr'  => 'tag',
				'type'  => 'text',
				'value' => 'default',
			],
			[
				'label' => __( '跳过文章数量' ),
				'attr'  => 'offset',
				'type'  => 'text',
				'value' => '0',
			],
			[
				'label' => __( '显示数量' ),
				'attr'  => 'num',
				'type'  => 'text',
				'value' => '12',
			],
			[
				'label' => __( '标题字数' ),
				'attr'  => 'cut',
				'type'  => 'text',
				'value' => '14',
			],
			[
				'label' => __( '显示分类名称作为标题' ),
				'attr'  => 'heading',
				'type'  => 'checkbox',
				'value' => true,
			],
			[
				'label' => __( '显示文章发布时间' ),
				'attr'  => 'time',
				'type'  => 'checkbox',
				'value' => false,
			],
		],
	] );


	// 创建图片列表UI
	shortcode_ui_register_for_shortcode( 'photo_list', [
		'label'         => '图文列表',
		'listItemImage' => 'dashicons-welcome-widgets-menus',
		'attrs'         => [
			[
				'label' => __( '文章类型' ),
				'attr'  => 'type',
				'type'  => 'text',
				'value' => 'post',
			],
			[
				'label' => __( '分类方法' ),
				'attr'  => 'tax',
				'type'  => 'text',
				'value' => 'category',
			],
			[
				'label' => __( '分类项目' ),
				'attr'  => 'tag',
				'type'  => 'text',
				'value' => 'default',
			],
			[
				'label' => __( '缩略图大小' ),
				'attr'  => 'thumbs',
				'type'  => 'text',
				'value' => 'tumbnails',
			],
			[
				'label'   => __( '缩略图位置' ),
				'attr'    => 'position',
				'type'    => 'select',
				'options' => [
					'left'  => '左侧',
					'top'   => '上面',
					'right' => '右边',
				],
			],
			[
				'label' => __( '显示数量' ),
				'attr'  => 'num',
				'type'  => 'text',
				'value' => '12',
			],
			[
				'label' => __( '标题字数' ),
				'attr'  => 'cut',
				'type'  => 'text',
				'value' => '10',
			],
			[
				'label' => __( '内容字数' ),
				'attr'  => 'content',
				'type'  => 'text',
				'value' => '200',
			],
			[
				'label' => __( '显示分类名称作为标题' ),
				'attr'  => 'heading',
				'type'  => 'checkbox',
				'value' => true,
			],
			[
				'label' => __( '附加CSS类' ),
				'attr'  => 'class',
				'type'  => 'text',
				'value' => '',
				'meta'  => [
					'placeholder' => '用来自定义CSS,可不填',
				],
			],
		],
	] );


	// 创建幻灯UI
	shortcode_ui_register_for_shortcode( 'slider', [
		'label'         => '幻灯',
		'listItemImage' => 'dashicons-slides',
		'attrs'         => [
			[
				'label' => __( '文章类型' ),
				'attr'  => 'type',
				'type'  => 'text',
				'value' => 'post',
			],
			[
				'label' => __( '分类方法' ),
				'attr'  => 'tax',
				'type'  => 'text',
				'value' => 'category',
			],
			[
				'label' => __( '分类项目' ),
				'attr'  => 'tag',
				'type'  => 'text',
				'value' => 'default',
			],
			[
				'label' => __( '缩略图大小' ),
				'attr'  => 'thumbs',
				'type'  => 'text',
				'value' => 'tumbnails',
			],
			[
				'label' => __( '显示数量' ),
				'attr'  => 'num',
				'type'  => 'text',
				'value' => '4',
			],
			[
				'label' => __( '标题字数' ),
				'attr'  => 'cut',
				'type'  => 'text',
				'value' => '30',
			],
			[
				'label' => __( '内容字数' ),
				'attr'  => 'content',
				'type'  => 'text',
				'value' => '60',
			],
			[
				'label' => __( '幻灯模式' ),
				'attr'  => 'mode',
				'type'  => 'text',
				'value' => 'horizontal',
			],
			[
				'label' => __( '切换速度' ),
				'attr'  => 'speed',
				'type'  => 'text',
				'value' => '500',
			],
			[
				'label' => __( '自动播放' ),
				'attr'  => 'auto',
				'type'  => 'checkbox',
				'value' => true,
			],
			[
				'label' => __( '鼠标滑过时暂停' ),
				'attr'  => 'autohover',
				'type'  => 'checkbox',
				'value' => true,
			],
			[
				'label' => __( '最少显示数量' ),
				'attr'  => 'minslides',
				'type'  => 'text',
				'value' => '1',
			],
			[
				'label' => __( '最多显示数量' ),
				'attr'  => 'maxslides',
				'type'  => 'text',
				'value' => '1',
			],
			[
				'label' => __( '图片宽度' ),
				'attr'  => 'slidewidth',
				'type'  => 'text',
				'value' => '360',
			],
			[
				'label' => __( '图片间距' ),
				'attr'  => 'slidewargin',
				'type'  => 'text',
				'value' => '10',
			],
			[
				'label' => __( '动画效果' ),
				'attr'  => 'easing',
				'type'  => 'text',
				'value' => 'swing',
			],
		],
	] );

}


// 带有结束标签的简码,比不带结束标签的多了一个inner_content
add_action( 'register_shortcode_ui', 'shortcode_ui_dev_advanced_example' );

function shortcode_ui_dev_advanced_example() {
	/*
	 * Define the Shortcode UI arguments.
	 */
	shortcode_ui_register_for_shortcode( 'shortcake_dev', [
		'label'         => esc_html__( 'Shortcake Dev', 'shortcode-ui-example' ),
		'listItemImage' => 'dashicons-editor-quote',

		// 内容元素
		'inner_content' => [
			'label' => esc_html__( '引用', 'shortcode-ui-example' ),
		],

		'attrs' => [
			[
				'label'       => esc_html__( '附件', 'shortcode-ui-example' ),
				'attr'        => 'attachment',
				'type'        => 'attachment',
				'libraryType' => [ 'image' ],
				'addButton'   => esc_html__( '选择图像', 'shortcode-ui-example' ),
				'frameTitle'  => esc_html__( '选择图像', 'shortcode-ui-example' ),
			],
			[
				'label'  => esc_html__( '引用来源', 'shortcode-ui-example' ),
				'attr'   => 'source',
				'type'   => 'text',
				'encode' => true,
				'meta'   => [
					'placeholder' => esc_html__( '从哪里引用的', 'shortcode-ui-example' ),
					'data-test'   => 1,
				],
			],
			[
				'label'    => esc_html__( '选择页面', 'shortcode-ui-example' ),
				'attr'     => 'page',
				'type'     => 'post_select',
				'query'    => [ 'post_type' => 'page' ],
				'multiple' => true,
			],
			[
				'label'  => esc_html__( '背景颜色', 'shortcode-ui-example' ),
				'attr'   => 'background-color',
				'type'   => 'color',
				'encode' => true,
				'meta'   => [
					'placeholder' => esc_html__( '16进制颜色值', 'shortcode-ui-example' ),
				],
			],
			[
				'label'   => esc_html__( '对齐', 'shortcode-ui-example' ),
				'attr'    => 'alignment',
				'type'    => 'select',
				'options' => [
					''      => esc_html__( '浮动', 'shortcode-ui-example' ),
					'left'  => esc_html__( '左对齐', 'shortcode-ui-example' ),
					'right' => esc_html__( '右对齐', 'shortcode-ui-example' ),
				],
			],
			[
				'label' => esc_html__( '年份', 'shortcode-ui-example' ),
				'attr'  => 'year',
				'type'  => 'number',
				'meta'  => [
					'placeholder' => 'YYYY',
					'min'         => '1990',
					'max'         => date_i18n( 'Y' ),
					'step'        => '1',
				],
			],
		],
	] );
}
