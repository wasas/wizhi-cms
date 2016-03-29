<?php
/*
Title: 代码
Setting: wizhi_theme_options
Tab: JS代码
Order: 1
*/

// head前js代码
piklist( 'field', [
        'type'        => 'textarea',
        'field'       => 'before_head',
        'label'       => 'head前代码',
        'description' => '一般是统计代码。',
        'attributes'  => [
            'rows'  => 10,
            'cols'  => 50,
            'class' => 'large-text',
        ],
    ] );

// body前代js码
piklist( 'field', [
        'type'        => 'textarea',
        'field'       => 'before_body',
        'label'       => 'body前代码',
        'description' => '统计代码或客服代码。',
        'attributes'  => [
            'rows'  => 10,
            'cols'  => 50,
            'class' => 'large-text',
        ],
    ] );

?>