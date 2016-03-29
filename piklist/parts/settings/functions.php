<?php
/*
Title: 网页特效
Setting: wizhi_theme_options
Tab: effect
Order: 1
*/

// 图片懒加载
piklist( 'field', [
        'type'    => 'checkbox',
        'field'   => 'lazyload',
        'label'   => __( '图片懒加载', 'piklist' ),
        'help'    => __( '对于图片多的网站，启用此项可显著提高页面打开速度。', 'piklist' ),
        'choices' => [
            'true' => __( '启用', 'piklist' ),
        ],
    ] );


// 下拉加载更多
piklist( 'field', [
        'type'    => 'checkbox',
        'field'   => 'ajaxloadmore',
        'label'   => __( '启用下拉加载', 'piklist' ),
        'help'    => __( '启用此项设置分页方式为下拉加载更多。', 'piklist' ),
        'choices' => [
            'true' => __( '启用', 'piklist' ),
        ],
    ] );


?>