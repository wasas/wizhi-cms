<?php

$post_types = [
	'prod'   => '产品',
	'case'   => '案例',
	'corp'   => '合作',
	'slider' => '幻灯',
];

$enabled_post_types = get_option( 'enabled_post_types', [ ] );

// 添加默认的文章类型和分类方法
foreach ( $post_types as $slug => $label ) {
	wizhi_create_types( $slug, $post_types[ $slug ], [ 'title', 'editor', 'thumbnail' ], true );
	wizhi_create_taxs( $slug . 'cat', $slug, $post_types[ $slug ] . '分类', true );
}