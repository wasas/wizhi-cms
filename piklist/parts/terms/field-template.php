<?php
/*
Title: 分类设置
Taxonomy: category
Order: 0
*/

$template = array_flip(get_page_templates());

piklist(
	'field',
	array(
		'type'       => 'select',
		'field'      => 'template',
		'label'      => '选择模板',
		'attributes' => array(
			'class' => 'text'
		),
		'choices'    => $template
	)
);

piklist(
	'field',
	array(
		'type'  => 'text',
		'field' => 'posts_per_page',
		'label' => '每页显示文章数'
	)
);

?>