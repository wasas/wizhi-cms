<?php
/*
Title: 链接地址
Post Type: links, post
Order: 0
Collapse: false
*/

piklist(
	'field',
	array(
		'type'       => 'text',
		'field'      => 'cus_links',
		'label'      => '链接地址',
		'attributes' => array(
			'class' => 'regular-text'
		)
	)
);

piklist(
	'field',
	array(
		'type'       => 'checkbox',
		'field'      => 'nofollow',
		'value'      => '',
		'attributes' => array(
			'class' => 'text'
		),
		'choices'    => array(
			'true' => 'Nofollow'
		)
	));

?>