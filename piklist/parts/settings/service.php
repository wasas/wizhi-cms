<?php
/*
Title: QQ 客服
Setting: wizhi_theme_options
Tab: JS代码
Order: 2
*/

// QQ客服号码
piklist( 'field', [
        'type'     => 'text',
        'field'    => 'qqservice',
        'help'     => __( '添加一个QQ号启用QQ在线客服，可添加多个。', 'piklist' ),
        'add_more' => true,
        'label'    => __( 'QQ客服号码' ),
    ] );

?>