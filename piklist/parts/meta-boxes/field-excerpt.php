<?php
/*
Title: 商品摘要
Post Type: product
Order: 0
Collapse: false
*/

piklist(
	'field',
	array(
		'type'    => 'editor',
		'field'   => 'pro_excerpt',
		'options' => array(
			'wpautop'       => true,
			'media_buttons' => true,
			'tabindex'      => '',
			'editor_css'    => '',
			'editor_class'  => '',
			'teeny'         => false,
			'dfw'           => false,
			'tinymce'       => true,
			'quicktags'     => true
		)
	));


piklist(
  'field',
  array(
    'type'    => 'textarea',
    'field'   => 'ludou_price',
    'attributes' => array(
      'rows' => 3,
      'cols' => 50,
      'class' => 'large-text'
    )
  ));

piklist(
	'field',
	array(
		'type'       => 'checkbox',
		'field'      => 'is_new',
		'value'      => 'true',
		'attributes' => array(
			'class' => 'text'
		),
		'choices'    => array(
			'true' => '让这个产品作为新产品'
		)
	));


piklist('field', array(
  'type'        => 'radio',
  'scope'       => 'post_meta',
  'field'       => 'field_name',
  'value'       => 'option2',
  'label'       => '单选',
  'attributes'  => array(
    'class' => 'text'
  ),
  'choices'     => array(
    '0' => '邮件自动发送（状态为交易成功或您设定的状态时）',
    '1' => '站内下载',
    '2' => '邮件自动发送和站内下载',
    '3' => '手动发货'
  )
));


?>