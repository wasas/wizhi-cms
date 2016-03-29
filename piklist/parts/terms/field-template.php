<?php
/*
Title: 分类设置
Taxonomy: category
Order: 0
*/

$template = wizhi_get_taxonomy_templates();

piklist( 'field', [
        'type'       => 'select',
        'field'      => 'template',
        'label'      => '选择模板',
        'attributes' => [
            'class' => 'text',
        ],
        'choices'    => $template,
    ] );

piklist( 'field', [
        'type'  => 'text',
        'field' => 'posts_per_page',
        'label' => '每页显示文章数',
    ] );

?>