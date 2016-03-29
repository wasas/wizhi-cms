<?php
/*
Title: 链接地址
Post Type: links, post
Order: 0
Collapse: false
*/

piklist( 'field', [
        'type'       => 'text',
        'field'      => 'cus_links',
        'label'      => '链接地址',
        'attributes' => [
            'class' => 'regular-text',
        ],
    ] );

piklist( 'field', [
        'type'       => 'checkbox',
        'field'      => 'nofollow',
        'value'      => '',
        'attributes' => [
            'class' => 'text',
        ],
        'choices'    => [
            'true' => 'Nofollow',
        ],
    ] );

?>