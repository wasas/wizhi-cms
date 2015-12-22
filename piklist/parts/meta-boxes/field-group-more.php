<?php
/*
Title: 销售链接
Post Type: product
Order: 0
Collapse: false
*/

piklist(
	'field',
	array(
      'type' => 'group',
      'field' => 'links',
      'add_more' => true,
      'fields' => array(
        array(
          'type' => 'text',
          'field' => 'lname',
          'columns' => 2,
          'attributes' => array(
            'placeholder' => '商城'
          )
        ),
        array(
          'type' => 'text',
          'field' => 'llink',
          'columns' => 10,
          'attributes' => array(
            'placeholder' => '链接'
          )
        )
      )
    )
  );

?>