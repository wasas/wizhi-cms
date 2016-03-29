<?php
/*
Title: 组件
Setting: wizhi_theme_options
Tab: frontend
Order: 0
*/
?>

<?php

piklist( 'field', [
        'type'        => 'checkbox',
        'field'       => 'statics',
        'template'    => 'field',
        'label'       => __( '加载前端资源', 'piklist' ),
        'description' => __( '是否加载插件自带的CSS个Js', 'piklist' ),
        'choices'     => [
            'true' => __( '加载', 'piklist' ),
        ],
    ] );

?>