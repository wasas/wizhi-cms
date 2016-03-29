<?php
/*
Title: 产品相册
Post Type: product
Order: 0
Collapse: false
*/

piklist( 'field', [
        'type'    => 'file',
        'field'   => 'pro_gallery',
        'scope'   => 'post_meta',
        'options' => [
            'modal_title' => __( '添加图片', 'piklist' ),
            'button'      => __( '添加', 'piklist' ),
        ],
    ] );

?>