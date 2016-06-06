<?php

$post_types = [
	'prod'   => '产品',
	'case'   => '案例',
	'corp'   => '合作',
	'slider' => '幻灯',
];

$fields = [
	[
		'type'    => 'checkbox-list',
		'name'    => 'enabled_post_types',
		'label'   => '启用的文章类型',
		'options' => $post_types,
	],
	[
		'type'    => 'checkbox',
		'name'    => 'is_enable_static',
		'label'   => '使用插件内置的样式',
	],
];


$args = [
	'slug'  => 'wizhi-cms-settings',
	'label' => '插件设置',
	'title' => 'Wizhi CMS 插件设置',
];

new WizhiOptionPage( $fields, $args );