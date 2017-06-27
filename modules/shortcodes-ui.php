<?php
add_action( 'init', 'wizhi_shortcode_ui' );

/**
 * 显示插件 UI, 基于 Shortcacke
 *
 * @package backend
 */
function wizhi_shortcode_ui() {

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
		'label'         => __( 'Button', 'wizhi' ),
		'listItemImage' => 'dashicons-external',
		'attrs'         => [
			[
				'label'   => __( 'Button color', 'wizhi' ),
				'attr'    => 'type',
				'type'    => 'select',
				'options' =>  DataOption::colors(),
			],
			[
				'label'   => __( 'Button Size', 'wizhi' ),
				'attr'    => 'size',
				'type'    => 'select',
				'options' => [
					'xsmall' => __( 'xSmall', 'wizhi' ),
					'small'  => __( 'Small', 'wizhi' ),
					''       => __( 'Normal', 'wizhi' ),
					'large'  => __( 'Large', 'wizhi' ),
					'xlarge' => __( 'xLarge', 'wizhi' ),
				],
			],
			[
				'label' => __( 'Button text', 'wizhi' ),
				'attr'  => 'text',
				'type'  => 'text',
				'meta'  => [
					'placeholder' => __( 'Button text', 'wizhi' ),
				],
			],
			[
				'label' => __( 'Button Link', 'wizhi' ),
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
		'label'         => __( 'Divider line', 'wizhi' ),
		'listItemImage' => 'dashicons-minus',
		'attrs'         => [
			[
				'label'   => __( 'Divider type', 'wizhi' ),
				'attr'    => 'type',
				'type'    => 'select',
				'options' => [
					'solid'  => __( 'Solid', 'wizhi' ),
					'shadow' => __( 'Shadow', 'wizhi' ),
				],
			],
		],
	] );


	// 显示内容标题
	shortcode_ui_register_for_shortcode( 'heading', [
			'label'         => __( 'Heading', 'wizhi' ),
			'listItemImage' => 'dashicons-editor-bold',
			'attrs'         => [
				[
					'label'   => __( 'Heading type', 'wizhi' ),
					'attr'    => 'type',
					'type'    => 'select',
					'options' => [
						'background' => __( 'With background', 'wizhi' ),
						'border'     => __( 'With border', 'wizhi' ),
					],
				],
				[
					'label' => __( 'Heading text', 'wizhi' ),
					'attr'  => 'content',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Please enter heading text', 'wizhi' ),
					],
				],
			],
		]

	);


	// 显示提示信息
	shortcode_ui_register_for_shortcode( 'alert', [
		'label'         => __( 'Alert', 'wizhi' ),
		'listItemImage' => 'dashicons-info',
		'attrs'         => [
			[
				'label'   => __( 'Alert type', 'wizhi' ),
				'attr'    => 'type',
				'type'    => 'select',
				'options' =>  DataOption::colors(),
			],
			[
				'label' => __( 'Alert content', 'wizhi' ),
				'attr'  => 'content',
				'type'  => 'textarea',
				'meta'  => [
					'placeholder' => __( 'Please enter alert content', 'wizhi' ),
				],
			],
		],
	] );


	// 创建显示页面内容UI
	shortcode_ui_register_for_shortcode( 'content', [
		'label'         => __( 'Page content', 'wizhi' ),
		'listItemImage' => 'dashicons-media-document',
		'attrs'         => [
			[
				'label' => __( 'Page ID', 'wizhi' ),
				'attr'  => 'id',
				'type'  => 'post_select',
				'query' => [ 'post_type' => 'page' ],
			],
			[
				'label' => __( 'Show text count', 'wizhi' ),
				'attr'  => 'cont',
				'type'  => 'text',
				'value' => '120',
			],
			[
				'label' => __( 'Show more link', 'wizhi' ),
				'attr'  => 'more',
				'type'  => 'checkbox',
				'value' => true,
			],
		],
	] );


	// 创建文章列表UI
	shortcode_ui_register_for_shortcode( 'list', [
		'label'         => __( 'Post title list', 'wizhi' ),
		'listItemImage' => 'dashicons-media-text',
		'attrs'         => [
			[
				'label'   => __( 'Post type', 'wizhi' ),
				'attr'    => 'type',
				'type'    => 'select',
				'value'   => 'post',
				'options' => DataOption::types(),
			],
			[
				'label'   => __( 'Taxonomy', 'wizhi' ),
				'attr'    => 'tax',
				'type'    => 'select',
				'value'   => 'category',
				'options' => DataOption::taxonomies(),
			],
			[
				'label' => __( 'Term', 'wizhi' ),
				'attr'  => 'tag',
				'type'  => 'text',
				'value' => 'default',
			],
			[
				'label' => __( 'Offset post count', 'wizhi' ),
				'attr'  => 'offset',
				'type'  => 'text',
				'value' => '0',
			],
			[
				'label' => __( 'Show post count', 'wizhi' ),
				'attr'  => 'num',
				'type'  => 'text',
				'value' => '12',
			],
			[
				'label' => __( 'Show title text count', 'wizhi' ),
				'attr'  => 'cut',
				'type'  => 'text',
				'value' => '14',
			],
			[
				'label' => __( 'Show term title as module heading', 'wizhi' ),
				'attr'  => 'heading',
				'type'  => 'checkbox',
				'value' => true,
			],
			[
				'label' => __( 'Show post datetime', 'wizhi' ),
				'attr'  => 'time',
				'type'  => 'checkbox',
				'value' => false,
			],
		],
	] );


	// 创建图片列表UI
	shortcode_ui_register_for_shortcode( 'media', [
		'label'         => __( 'Media list', 'wizhi' ),
		'listItemImage' => 'dashicons-welcome-widgets-menus',
		'attrs'         => [
			[
				'label'   => __( 'Post type', 'wizhi' ),
				'attr'    => 'type',
				'type'    => 'select',
				'value'   => 'post',
				'options' => DataOption::types(),
			],
			[
				'label'   => __( 'Taxonomy', 'wizhi' ),
				'attr'    => 'tax',
				'type'    => 'select',
				'value'   => 'category',
				'options' => DataOption::taxonomies(),
			],
			[
				'label' => __( 'Term', 'wizhi' ),
				'attr'  => 'tag',
				'type'  => 'text',
				'value' => 'default',
			],
			[
				'label'   => __( 'Thumbnail size', 'wizhi' ),
				'attr'    => 'thumbs',
				'type'    => 'select',
				'value'   => 'thumbnail',
				'options' => DataOption::size(),
			],
			[
				'label'   => __( 'Thumbnail position', 'wizhi' ),
				'attr'    => 'position',
				'type'    => 'select',
				'options' => [
					'left'  => __( 'Left', 'wizhi' ),
					'top'   => __( 'Top', 'wizhi' ),
					'right' => __( 'Right', 'wizhi' ),
				],
			],
			[
				'label' => __( 'Show post count', 'wizhi' ),
				'attr'  => 'num',
				'type'  => 'text',
				'value' => '12',
			],
			[
				'label' => __( 'Show title text count', 'wizhi' ),
				'attr'  => 'cut',
				'type'  => 'text',
				'value' => '14',
			],
			[
				'label' => __( 'Show Content text count', 'wizhi' ),
				'attr'  => 'content',
				'type'  => 'text',
				'value' => '200',
			],
			[
				'label' => __( 'Show term title as module heading', 'wizhi' ),
				'attr'  => 'heading',
				'type'  => 'checkbox',
				'value' => true,
			],
			[
				'label' => __( 'Addon CSS class name', 'wizhi' ),
				'attr'  => 'class',
				'type'  => 'text',
				'value' => '',
				'meta'  => [
					'placeholder' => '',
				],
			],
		],
	] );


	// 创建幻灯UI
	shortcode_ui_register_for_shortcode( 'slider', [
		'label'         => __( 'Slider', 'wizhi' ),
		'listItemImage' => 'dashicons-slides',
		'attrs'         => [
			[
				'label'   => __( 'Post type', 'wizhi' ),
				'attr'    => 'type',
				'type'    => 'select',
				'value'   => 'post',
				'options' => DataOption::types(),
			],
			[
				'label'   => __( 'Taxonomy', 'wizhi' ),
				'attr'    => 'tax',
				'type'    => 'select',
				'value'   => 'category',
				'options' => DataOption::taxonomies(),
			],
			[
				'label' => __( 'Term', 'wizhi' ),
				'attr'  => 'tag',
				'type'  => 'text',
				'value' => 'default',
			],
			[
				'label'   => __( 'Thumbnail size', 'wizhi' ),
				'attr'    => 'thumbs',
				'type'    => 'select',
				'value'   => 'thumbnail',
				'options' => DataOption::sizes(),
			],
			[
				'label' => __( 'Show post count', 'wizhi' ),
				'attr'  => 'num',
				'type'  => 'text',
				'value' => '12',
			],
			[
				'label' => __( 'Show title text count', 'wizhi' ),
				'attr'  => 'cut',
				'type'  => 'text',
				'value' => '14',
			],
			[
				'label' => __( 'Show Content text count', 'wizhi' ),
				'attr'  => 'content',
				'type'  => 'text',
				'value' => '200',
			],
			[
				'label' => __( 'Slider mode', 'wizhi' ),
				'attr'  => 'mode',
				'type'  => 'text',
				'value' => 'horizontal',
			],
			[
				'label' => __( 'Slider speed', 'wizhi' ),
				'attr'  => 'speed',
				'type'  => 'text',
				'value' => '500',
			],
			[
				'label' => __( 'Auto play', 'wizhi' ),
				'attr'  => 'auto',
				'type'  => 'checkbox',
				'value' => true,
			],
			[
				'label' => __( 'Pause on mouse hover', 'wizhi' ),
				'attr'  => 'autohover',
				'type'  => 'checkbox',
				'value' => true,
			],
			[
				'label' => __( 'Minimum slider count', 'wizhi' ),
				'attr'  => 'minslides',
				'type'  => 'text',
				'value' => '1',
			],
			[
				'label' => __( 'Maximum slider count', 'wizhi' ),
				'attr'  => 'maxslides',
				'type'  => 'text',
				'value' => '1',
			],
			[
				'label' => __( 'Slider width', 'wizhi' ),
				'attr'  => 'slidewidth',
				'type'  => 'text',
				'value' => '360',
			],
			[
				'label' => __( 'Slider margin', 'wizhi' ),
				'attr'  => 'slidewargin',
				'type'  => 'text',
				'value' => '10',
			],
			[
				'label' => __( 'Animation', 'wizhi' ),
				'attr'  => 'easing',
				'type'  => 'text',
				'value' => 'swing',
			],
		],
	] );

}