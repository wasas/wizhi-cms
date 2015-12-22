<?php
/*
Title: 用户
Setting: wizhi_theme_options
Order: 1
*/


piklist(
	'field',
	array(
		'type'        => 'checkbox',
		'field'       => 'multiple_user_roles',
		'label'       => __('多用户角色', 'piklist'),
		'description' => __('可以给一个用户分配多个角色', 'piklist'),
		'help'        => __('修改用户角色选择为多选', 'piklist'),
		'choices'     => array(
			'true' => __('允许', 'piklist')
		)
	)
);


?>