<?php
/*
Title: 文档分类
Post Type: codex
Order: 0
Context: side
Priority: default
Collapse: false
*/

piklist(
	'field',
	array(
		'type' => 'select',
		'field' => 'function',
		'scope' => 'taxonomy',
		'label' => '文档分类',
		'columns' => 12,
		'choices' => piklist(
			get_terms(
				'function',
				array(
					'hide_empty' => false
				)
			),
			array(
				'term_id',
				'name'
			)
		)
	)
);


piklist(
	'field',
	array(
		'type' => 'select',
		'field' => 'template',
		'scope' => 'taxonomy',
		'label' => '模板标签',
		'columns' => 12,
		'choices' => piklist(
			get_terms(
				'template',
				array(
					'hide_empty' => false
				)
			),
			array(
				'term_id',
				'name'
			)
		)
	)
);


piklist(
	'field',
	array(
		'type' => 'select',
		'field' => 'action',
		'scope' => 'taxonomy',
		'label' => '数据操作',
		'columns' => 12,
		'choices' => piklist(
			get_terms(
				'action',
				array(
					'hide_empty' => false
				)
			),
			array(
				'term_id',
				'name'
			)
		)
	)
);