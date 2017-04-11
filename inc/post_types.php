<?php
/**
 * 快速添加文章类型和分类方法
 */

if ( ! function_exists( "wizhi_create_types" ) ) {
	/**
	 * 快速添加文章类型
	 *
	 * @since   wizhi 1.0
	 *
	 * @param string  $slug       文章类型名称
	 * @param string  $name       文章类型菜单名称
	 * @param array   $support    文章类型支持的功能
	 * @param boolean $is_publish 文章类型是否在前后台可见
	 * @param string  $icon       后台使用的 dashicon 图标
	 *
	 * @package backend
	 *
	 * @usage   wizhi_create_types( 'prod', '产品', [ 'title', 'editor', 'thumbnail' ], true );
	 */
	function wizhi_create_types( $slug, $name, $support, $is_publish, $icon = 'dashicons-networking' ) {

		//文章类型的标签
		$labels = [
			'name'               => $name,
			'singular_name'      => $name,
			'add_new'            => __( 'Add New', 'wizhi' ) . $name,
			'add_new_item'       => __( 'Add New', 'wizhi' ) . $name,
			'edit_item'          => __( 'Edit', 'wizhi' ) . $name,
			'new_item'           => __( 'New', 'wizhi' ) . $name,
			'all_items'          => __( 'All', 'wizhi' ) . $name,
			'view_item'          => __( 'View', 'wizhi' ) . $name,
			'search_items'       => __( 'Search', 'wizhi' ) . $name,
			'not_found'          => sprintf( __( 'Could not find %s', 'wizhi' ), $name ),
			'not_found_in_trash' => sprintf( __( 'Could not find %s in trash', 'wizhi' ), $name ),
			'menu_name'          => $name,
		];

		//注册文章类型需要的参数
		$args = [
			'labels'             => $labels,
			'public'             => $is_publish,
			'publicly_queryable' => $is_publish,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => $is_publish,
			'rewrite'            => [ 'slug' => $slug ],
			'capability_type'    => 'post',
			'has_archive'        => $is_publish,
			'hierarchical'       => false,
			'supports'           => $support,
			'menu_position'      => 5,
			'menu_icon'          => $icon,
		];

		if ( strlen( $slug ) > 0 ) {
			register_post_type( $slug, $args );
		}

		flush_rewrite_rules();
	}
}


if ( ! function_exists( "wizhi_create_taxs" ) ) {
	/**
	 * 快速添加分类方法
	 *
	 * @since   wizhi 1.0
	 *
	 * @param string  $tax_slug     分类法名称
	 * @param string  $post_type    关联到的文章类型的名称
	 * @param string  $tax_name     分类法菜单名称
	 * @param boolean $hierarchical 是否允许有父级分类
	 *
	 * @package backend
	 *
	 * @usage   wizhi_create_taxs('prodcat', 'prod', '产品分类', true);
	 */
	function wizhi_create_taxs( $tax_slug, $post_type, $tax_name, $hierarchical = true ) {

		//分类法的标签
		$labels = [
			'name'              => $tax_name,
			'singular_name'     => $tax_name,
			'search_items'      => __( 'Search', 'wizhi' ) . $tax_name,
			'all_items'         => __( 'All', 'wizhi' ) . $tax_name,
			'parent_item'       => __( 'Parent', 'wizhi' ) . $tax_name,
			'parent_item_colon' => __( 'Parent', 'wizhi' ) . $tax_name,
			'edit_item'         => __( 'Edit', 'wizhi' ) . $tax_name,
			'update_item'       => __( 'Upgrade', 'wizhi' ) . $tax_name,
			'add_new_item'      => __( 'Add New', 'wizhi' ) . $tax_name,
			'new_item_name'     => sprintf( __( 'New %s', 'wizhi' ), $tax_name ),
			'menu_name'         => $tax_name,
		];

		//分类法参数
		$args = [
			'hierarchical'      => $hierarchical,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => $tax_slug ],
			'sort'              => true,
		];

		if ( strlen( $tax_slug ) > 0 ) {
			register_taxonomy( $tax_slug, [ $post_type ], $args );
		}

		flush_rewrite_rules();
	}
}