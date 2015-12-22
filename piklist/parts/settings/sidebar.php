<?php
/*
Title: 侧边栏效果
Setting: wizhi_theme_options
Tab: sidebar
Order: 1
*/

// 启用浮动侧边栏
piklist(
	'field',
	array(
		'type'        => 'checkbox',
		'field'       => 'pin',
		'label'       => __( '启用浮动侧边栏', 'piklist' ),
		'help'        => __( '页面非常长时，启用此项可显示一个浮动侧边栏。', 'piklist' ),
		'choices'     => array(
			'true' => __( '启用', 'piklist' )
		)
	)
);


?>