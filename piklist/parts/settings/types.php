<?php
/*
Title: 文章类型
Setting: wizhi_theme_options
Tab: 文章类型
Order: 1
*/

// 支持的文章类型设置
piklist(
	'field',
	array(
		'type'        => 'checkbox',
		'field'       => 'register_types',
		'label'       => __( '文章类型', 'piklist' ),
		'help'        => __( '每个文章类型都有相应的特色功能', 'piklist' ),
		'choices'     => array(
			'slider' => __( '幻灯', 'piklist' ),
			'product' => __( '产品', 'piklist' ),
			'case' => __( '案例', 'piklist' ),
			'team' => __( '团队', 'piklist' ),
			'ord' => __( '订单', 'piklist' ),
			'link' => __( '链接', 'piklist' ),
		)
	)
);

?>