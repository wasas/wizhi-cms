<?php

/**
 * 所有简码模板使用的功能函数, 这些功能是可重用的, 并且被很多简码重用
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 经常的使用的参数保存为函数, 以便更方便管理和使用
 */
function wizhi_functions_display_order() {
    $output = [ ];
    $output[ 'author' ] = __( '作者', 'pbwizhi' );
    $output[ 'date' ] = __( '日期', 'pbwizhi' );
    $output[ 'title' ] = __( '标题', 'pbwizhi' );
    $output[ 'rand' ] = __( '随机', 'pbwizhi' );

    return $output;
}


/**
 * 经常的使用的参数保存为函数, 以便更方便管理和使用
 */
function wizhi_functions_display_dir() {
    $output = [ ];
    $output[ 'ASC' ] = __( '升序', 'pbwizhi' );
    $output[ 'DESC' ] = __( '降序', 'pbwizhi' );

    return $output;
}


/**
 * @param string $type 自定义文章类型别名
 * @param string $id   文章 ID
 *
 * @return array 文章列表数组, ID 为键, 标题为值
 */
function wizhi_functions_posttype_list( $type = "forum", $id = "false" ) {
    $args = [
        'post_type'      => $type,
        'posts_per_page' => '-1',
    ];
    $loop = new WP_Query( $args );

    $output = [
        0 => sprintf( '— %s —', __( '选择', 'pbwizhi' ) ),
    ];

    if( $loop->have_posts() ) {
        while ( $loop->have_posts() ) : $loop->the_post();
            $fieldout = get_the_title();
            if( $id != "false" ) {
                $fieldout .= " (" . get_the_ID() . ")";
            }
            $output[ get_the_ID() ] = $fieldout;
        endwhile;
    }
    wp_reset_postdata();

    return $output;
}


/**
 * 获取分类法列表 设置为标签用来获取标签, 其他值被作为分类法对待
 */
function wizhi_functions_taxonomy_list( $type = "taxonomy" ) {
    $output = [
        0 => sprintf( '— %s —', __( '选择', 'pbwizhi' ) ),
    ];
    foreach ( get_taxonomies() as $taxonomy ) {
        $tax = get_taxonomy( $taxonomy );
        if( ( !$tax->show_tagcloud || empty( $tax->labels->name ) ) && $type == "tag" ) {
            continue;
        }
        $output[ esc_attr( $taxonomy ) ] = esc_attr( $tax->labels->name );
    }

    return $output;
}


/**
 * 编码指定的分类法中的分类项目为数组
 *
 * @param string $taxonomyName 分类法名称
 *
 * @return array
 */
function wizhi_functions_term_list( $taxonomyName = 'post_tag' ) {
    $terms = get_terms( $taxonomyName, [
        'parent'     => 0,
        'hide_empty' => false,
    ] );

    $output = [
        0 => sprintf( '— %s —', __( '选择', 'pbwizhi' ) ),
    ];

    if( is_wp_error( $terms ) ) {
        return $output;
    }

    foreach ( $terms as $term ) {

        $output[ $term->slug ] = $term->name;
        $term_children = get_term_children( $term->term_id, $taxonomyName );

        if( is_wp_error( $term_children ) ) {
            continue;
        }

        foreach ( $term_children as $term_child_id ) {

            $term_child = get_term_by( 'id', $term_child_id, $taxonomyName );

            if( is_wp_error( $term_child ) ) {
                continue;
            }

            $output[ $term_child->slug ] = $term_child->name;
        }

    }

    return $output;
}