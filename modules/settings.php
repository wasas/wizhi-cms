<?php

$post_types = wizhi_post_types();

$fields = [
	[
		'type'    => 'multi-checkbox',
		'name'    => 'enabled_post_types',
		'label'   => __( '启用的文章类型', 'wizhi' ),
		'options' => $post_types,
	],
	[
		'type'  => 'checkbox',
		'name'  => 'is_enable_css',
		'label' => __( '使用内置的CSS', 'wizhi' ),
	],
	[
		'type'  => 'checkbox',
		'name'  => 'is_enable_js',
		'label' => __( '使用内置JavaScript', 'wizhi' ),
	],
	[
		'type'  => 'checkbox',
		'name'  => 'is_enable_font',
		'label' => __( '是否加载插件自带的 FontAwesome 字体图标', 'wizhi' ),
	],
	[
		'type'  => 'checkbox',
		'name'  => 'is_enable_builder',
		'label' => __( '启用可视化编辑器（需要安装并激活 Shortcake 插件）', 'wizhi' ),
	],
];


$args = [
	'parent' => 'options-general.php',
	'slug'   => 'wizhi-cms-settings',
	'label'  => __( '插件设置', 'wizhi' ),
	'title'  => __( 'Wizhi CMS 插件设置', 'wizhi' ),
];

new WizhiOptionPage( $fields, $args );