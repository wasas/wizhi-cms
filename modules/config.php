<?php

function wizhi_post_types() {
	$post_types = [
		'prod'     => '产品',
		'case'     => '案例',
		'corp'     => '合作',
		'team'     => '团队',
		'slider'   => '幻灯',
		'faq'      => '问题',
		'download' => '下载',
	];

	return $post_types;
}


function wizhi_post_types_icon() {
	$post_types_icons = [
		'prod'     => 'dashicons-cart',
		'case'     => 'dashicons-awards',
		'corp'     => 'dashicons-universal-access',
		'team'     => 'dashicons-groups',
		'slider'   => 'dashicons-slides',
		'faq'      => 'dashicons-editor-help',
		'download' => 'dashicons-download',
	];

	return $post_types_icons;
}
