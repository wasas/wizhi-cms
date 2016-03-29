<?php
/*
Title: 销售链接
Post Type: product
Order: 0
Collapse: false
*/

piklist( 'field', [
        'type'     => 'group',
        'field'    => 'links',
        'add_more' => true,
        'fields'   => [
            [
                'type'       => 'text',
                'field'      => 'lname',
                'columns'    => 2,
                'attributes' => [
                    'placeholder' => '商城',
                ],
            ],
            [
                'type'       => 'text',
                'field'      => 'llink',
                'columns'    => 10,
                'attributes' => [
                    'placeholder' => '链接',
                ],
            ],
        ],
    ] );

?>